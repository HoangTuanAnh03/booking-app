<?php

namespace App\Repository\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\NotFoundException;
use App\Models\Court;
use App\Models\CourtSpecialTime;
use App\Models\Field;
use App\Models\SportType;
use App\Repository\ICourtSlotRepository;
use App\Repository\IFieldRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FieldRepository implements IFieldRepository
{
    private ICourtSlotRepository $courtSlotRepository;

    /**
     * @param ICourtSlotRepository $courtSlotRepository
     */
    public function __construct(ICourtSlotRepository $courtSlotRepository)
    {
        $this->courtSlotRepository = $courtSlotRepository;
    }


    public function show(int $perPage, string $id)
    {

        return Field::with('openingHourToday')
            ->where('venue_id', $id)
            ->paginate($perPage);
    }

    public function getById(string $id)
    {
        return Field::with('openingHoursWeek')->where('field_id', $id)->first();
    }

    public function store(array $data)
    {
        return Field::create($data);
    }

    public function update(array $data, string $id)
    {
        $Field = Field::where('field_id', $id)->first();
        if (!$Field) return null;

        $Field->update($data);
        return $Field;
    }

    public function delete(string $id)
    {
        $Field = Field::where('field_id', $id)->first();
        if (!$Field) return null;

        $Field->delete();
        return $Field;
    }

    public function getOwnerId(string $fieldId)
    {
        return DB::table('fields')
            ->join('venues', 'fields.venue_id', '=', 'venues.venue_id')
            ->where('fields.field_id', $fieldId)
            ->value('venues.owner_id');
    }

    /**
     * @throws ErrorException
     * @throws NotFoundException
     */
    public function getCourtsByFieldAndDate(string $fieldId, string $date): array
    {
        $date = $this->parseDate($date);
        $dayOfWeek = $date->format('l');

        $field = $this->fetchField($fieldId, $dayOfWeek);
        $openingHours = $this->getOpeningHours($field);
        [$openingTime, $closingTime] = $this->validateOpeningHours($openingHours);

        $courts = $this->fetchCourts($fieldId, $date);
        $courtSpecialTimes = $this->fetchCourtSpecialTimes($date, $courts);
        $courtSlotsLocked = $this->courtSlotRepository->getLockedCourtSlotsByDateAndCourts(
            $date,
            $courts->pluck('court_id')->toArray()
        );

        return $this->generateCourtTimeSlots(
            $courts,
            $field,
            $openingTime,
            $closingTime,
            $date,
            $courtSlotsLocked,
            $courtSpecialTimes
        );
    }

    /**
     * @throws ErrorException
     */
    private function parseDate(string $date): Carbon
    {
        try {
            return Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
        } catch (\Exception $e) {
            throw new ErrorException('Invalid date format. Use Y-m-d.');
        }
    }

    /**
     * @throws NotFoundException
     */
    private function fetchField(string $fieldId, string $dayOfWeek): Field
    {
        $field = Field::where('field_id', $fieldId)
            ->where('is_active', true)
            ->whereHas('venue', fn($query) => $query->where('status', 'active'))
            ->with([
                'openingHours' => fn($query) => $query
                    ->where('day_of_week', $dayOfWeek)
                    ->select('opening_id', 'field_id', 'opening_time', 'closing_time'),
                'prices' => fn($query) => $query
                    ->where('day_of_week', $dayOfWeek)
                    ->select('field_price_id', 'field_id', 'start_time', 'end_time', 'price', 'min_rental')
                    ->orderBy('start_time', 'asc'),
            ])
            ->select('field_id', 'field_name', 'default_price')
            ->first();

        if (!$field) {
            throw new NotFoundException('Field not found.');
        }

        return $field;
    }

    /**
     * @throws ErrorException
     */
    private function getOpeningHours(Field $field): Collection
    {
        $openingHours = $field->openingHours;
        if ($openingHours->isEmpty()) {
            throw new ErrorException('Field opening hours not available.');
        }
        return $openingHours;
    }

    /**
     * @throws ErrorException
     */
    private function validateOpeningHours(Collection $openingHours): array
    {
        $openingHour = $openingHours->first();
        $openingTime = Carbon::parse($openingHour->opening_time);
        $closingTime = Carbon::parse($openingHour->closing_time);

        if ($openingTime->gte($closingTime)) {
            throw new ErrorException('Invalid opening hours configuration.');
        }

        return [$openingTime, $closingTime];
    }

    private function fetchCourts(string $fieldId, Carbon $date): Collection
    {
        return Court::where('field_id', $fieldId)
            ->with([
                'slots' => fn($query) => $query
                    ->where('date', $date->format('Y-m-d'))
                    ->select('slot_id', 'court_id', 'start_time', 'end_time', 'is_looked', 'locked_by_owner')
                    ->orderBy('start_time', 'asc'),
            ])
            ->select('court_id', 'court_name')
            ->get()
            ->sortBy('court_name');
    }

    private function fetchCourtSpecialTimes(Carbon $date, Collection $courts): Collection
    {
        return CourtSpecialTime::whereDate('date', $date->format('Y-m-d'))
            ->whereIn('court_id', $courts->pluck('court_id'))
            ->get()
            ->groupBy('court_id')
            ->map(fn($courtTimes) => $courtTimes->keyBy('start_time'));
    }

    private function generateCourtTimeSlots(
        Collection $courts,
        Field      $field,
        Carbon     $openingTime,
        Carbon     $closingTime,
        Carbon     $date,
        array      $courtSlotsLocked,
        Collection $courtSpecialTimes
    ): array
    {
        $result = [];
        foreach ($courts as $court) {
            $specialTimes = $courtSpecialTimes->get($court->court_id, collect());
            $courtSlots = $courtSlotsLocked[$court->court_id] ?? [];
            $timeSlots = $this->generateTimeSlots($field, $openingTime, $closingTime, $date, $courtSlots, $specialTimes);

            $result[$court->court_id] = [
                'name' => $court->court_name ?? 'Unnamed Court',
                'time_slots' => $timeSlots,
            ];
        }
        return $result;
    }

    private function generateTimeSlots($field, Carbon $openingTime, Carbon $closingTime, Carbon $date, $courtSlots, Collection $specialTimes = null): Collection
    {
        $timeSlots = collect();
        $isToday = $date->isToday();
        $now = Carbon::now();

        if ($field->prices->isNotEmpty()) {
            foreach ($field->prices as $price) {
                $start = Carbon::parse($price->start_time);
                $end = Carbon::parse($price->end_time);

                if ($this->isValidPriceRange($start, $end, $openingTime, $closingTime)) {
                    $this->generateSlotsForRange(
                        $timeSlots,
                        $start,
                        $end,
                        $price->price,
                        $price->min_rental,
                        $isToday,
                        $now,
                        $courtSlots,
                        $specialTimes,
                        $closingTime
                    );
                }
            }
        } else {
            $this->generateSlotsForRange(
                $timeSlots,
                $openingTime,
                $closingTime,
                $field->default_price,
                30,
                $isToday,
                $now,
                $courtSlots,
                $specialTimes,
                $closingTime
            );
        }

        return $timeSlots->sortBy(fn($slot) => explode('-', $slot['time'])[0])->values();
    }

    private function isValidPriceRange(Carbon $start, Carbon $end, Carbon $openingTime, Carbon $closingTime): bool
    {
        return $start->gte($openingTime) && $end->lte($closingTime) && $start->lt($end);
    }

    private function generateSlotsForRange(
        Collection  $timeSlots,
        Carbon      $start,
        Carbon      $end,
        float       $defaultPrice,
        int         $defaultMinRental,
        bool        $isToday,
        Carbon      $now,
        array       $courtSlots,
        ?Collection $specialTimes,
        Carbon      $closingTime
    ): void
    {
        $current = $start->copy();

        while ($current->lt($end)) {
            // Mặc định giá và thời gian thuê nếu không có special times
            $price = $defaultPrice;
            $minRental = $defaultMinRental;

            // Lấy thời gian kết thúc của slot
            $slotEnd = $this->getSlotEndTime($current, $minRental, $specialTimes);

            // Kiểm tra nếu slot vượt quá thời gian kết thúc hoặc thời gian đóng cửa
            if ($slotEnd->gt($end) || $slotEnd->gt($closingTime)) {
                break;
            }

            // Kiểm tra xem slot có bị hết hạn chưa nếu là ngày hôm nay
            if ($isToday && $this->isSlotExpired($slotEnd, $now, $minRental)) {
                $current = $slotEnd;
                continue;
            }

            // Kiểm tra xem slot có bị khóa hay không
            $slotStatus = isset($courtSlots[$current->format('H:i')]) ? 'locked' : 'available';

            // Nếu có special times, sử dụng giá và thời gian thuê từ special times
            if (isset($specialTimes[$current->format('H:i:s')])) {
                $data = $specialTimes[$current->format('H:i:s')];
                $price = $data['price'];
                $minRental = $data['min_rental'];
            }

            // Tạo slot mới và thêm vào timeSlots
            $timeSlots->push($this->createTimeSlot(
                $current,
                $slotEnd,
                $price,
                $minRental,
                $slotStatus // Trạng thái của slot: 'locked' hoặc 'available'
            ));

            // Cập nhật current cho vòng lặp tiếp theo
            $current = $slotEnd;
        }
    }

    private function getSlotEndTime(Carbon $current, int $defaultMinRental, ?Collection $specialTimes): Carbon
    {
        if ($specialTimes && $specialTimes->has($current->format('H:i:s'))) {
            $data = $specialTimes[$current->format('H:i:s')];
            return Carbon::parse($data['end_time']);
        }

        return $current->copy()->addMinutes($defaultMinRental);
    }

    private function isSlotExpired(Carbon $slotEnd, Carbon $now, int $minRental): bool
    {
        return $slotEnd->lt($now->copy()->addMinutes($minRental / 2));
    }

    private function createTimeSlot(Carbon $start, Carbon $end, float $price, int $minRental, string $status): array
    {
        $startFormatted = $start->format('H:i');
        $endFormatted = $end->format('H:i');

        return [
            'start_time' => $startFormatted,
            'end_time' => $endFormatted,
            'time' => sprintf('%s-%s', $startFormatted, $endFormatted),
            'price' => $price,
            'status' => $status,
            'min_rental' => $minRental,
        ];
    }

    public function getFieldStas(): array
    {
        $count = Field::distinct('sport_type_id')->count('sport_type_id');
        $sports = SportType::select('name')
            ->withCount('fields')
            ->get();
        return [
            'count' => $count,
            'detail' => $sports,
        ];
    }
}
