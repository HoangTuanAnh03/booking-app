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

        $fields = Field::with('openingHourToday')
            ->where('venue_id', $id)
            ->paginate($perPage);

        $sortedFields = $fields->getCollection()->sortByDesc(function($field) {
            return $field->openingHourToday ? 1 : 0;
        })->values();;

        $fields->setCollection($sortedFields);

        return $fields;
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

        $baseTimeLine = $this->generateBaseTimeLine($field, $openingTime, $closingTime, $date);
        return [
            'base_time_line' => $baseTimeLine,
            'courts' => $this->generateCourtTimeSlots(
                $courts,
                $field,
                $openingTime,
                $closingTime,
                $date,
                $courtSlotsLocked,
                $courtSpecialTimes,
                $baseTimeLine
            )
        ];
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

    private function generateBaseTimeLine(Field $field, Carbon $openingTime, Carbon $closingTime, Carbon $date): array
    {
        $baseTimeLine = [];
        $isToday = $date->isToday();
        $now = Carbon::now();

        if ($field->prices->isNotEmpty()) {
            foreach ($field->prices as $price) {
                $start = Carbon::parse($price->start_time);
                $end = Carbon::parse($price->end_time);
                $minRental = $price->min_rental;

                if ($this->isValidPriceRange($start, $end, $openingTime, $closingTime)) {
                    $baseTimeLine = array_merge(
                        $baseTimeLine,
                        $this->buildBaseTimeSlots($start, $end, $openingTime, $closingTime, $minRental, $isToday, $now)
                    );
                }
            }
        } else {
            $minRental = 30;
            $baseTimeLine = $this->buildBaseTimeSlots($openingTime, $closingTime, $openingTime, $closingTime, $minRental, $isToday, $now);
        }

        return $baseTimeLine;
    }

    private function buildBaseTimeSlots(
        Carbon $start,
        Carbon $end,
        Carbon $openingTime,
        Carbon $closingTime,
        int $minRental,
        bool $isToday,
        Carbon $now
    ): array {
        $slots = [];
        $current = $start->copy();

        if ($isToday && $current->lt($start)) {
            $current = $start->copy();
        }

        while ($current->lt($end) && $current->gte($start)) {
            $slotEnd = $current->copy()->addMinutes($minRental);
            if ($slotEnd->gt($end) || $slotEnd->gt($closingTime)) {
                break;
            }

            if ($isToday && $this->isSlotExpired($slotEnd, $now, $minRental)) {
                $current = $slotEnd;
                continue;
            }

            $slots[] = [
                'start_time' => $current->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'time' => sprintf('%s-%s', $current->format('H:i'), $slotEnd->format('H:i')),
                'duration' => $minRental
            ];
            $current = $slotEnd;
        }

        return $slots;
    }

    private function generateCourtTimeSlots(
        Collection $courts,
        Field      $field,
        Carbon     $openingTime,
        Carbon     $closingTime,
        Carbon     $date,
        array      $courtSlotsLocked,
        Collection $courtSpecialTimes,
        array      $baseTimeLine
    ): array
    {
        $result = [];
        foreach ($courts as $court) {
            $specialTimes = $courtSpecialTimes->get($court->court_id, collect());
            $courtSlots = $courtSlotsLocked[$court->court_id] ?? [];
            $timeSlots = $this->generateTimeSlots($field, $openingTime, $closingTime, $date, $courtSlots, $specialTimes, $baseTimeLine);

            $result[$court->court_id] = [
                'name' => $court->court_name ?? 'Unnamed Court',
                'time_slots' => $timeSlots,
            ];
        }
        return $result;
    }

    private function generateTimeSlots(
        $field,
        Carbon $openingTime,
        Carbon $closingTime,
        Carbon $date,
        $courtSlots,
        Collection $specialTimes,
        array $baseTimeLine
    ): Collection
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
                        $closingTime,
                        $baseTimeLine
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
                $closingTime,
                $baseTimeLine
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
        Carbon      $closingTime,
        array       $baseTimeLine
    ): void
    {
        $current = $start->copy();

        while ($current->lt($end)) {
            $price = $defaultPrice;
            $minRental = $defaultMinRental;

            $slotEnd = $this->getSlotEndTime($current, $minRental, $specialTimes);

            if ($slotEnd->gt($end) || $slotEnd->gt($closingTime)) {
                break;
            }

            if ($isToday && $this->isSlotExpired($slotEnd, $now, $minRental)) {
                $current = $slotEnd;
                continue;
            }

            $slotStatus = isset($courtSlots[$current->format('H:i')]) ? 'locked' : 'available';

            if (isset($specialTimes[$current->format('H:i:s')])) {
                $data = $specialTimes[$current->format('H:i:s')];
                $price = $data['price'];
                $minRental = $data['min_rental'];
                $slotEnd = Carbon::parse($data['end_time']);
            }

            $timeSlots->push($this->createTimeSlot(
                $current,
                $slotEnd,
                $price,
                $minRental,
                $slotStatus,
                $baseTimeLine
            ));

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

    private function createTimeSlot(
        Carbon $start,
        Carbon $end,
        float  $price,
        int    $minRental,
        string $status,
        array  $baseTimeLine
    ): array
    {
        $startFormatted = $start->format('H:i');
        $endFormatted = $end->format('H:i');

        $colspan = $this->calculateColspan($start, $end, $baseTimeLine);

        return [
            'start_time' => $startFormatted,
            'end_time' => $endFormatted,
            'time' => sprintf('%s-%s', $startFormatted, $endFormatted),
            'price' => $price,
            'status' => $status,
            'min_rental' => $minRental,
            'colspan' => $colspan,
        ];
    }

    private function calculateColspan(Carbon $start, Carbon $end, array $baseTimeLine): int
    {
        $startTime = $start->format('H:i');
        $endTime = $end->format('H:i');
        $colspan = 0;

        foreach ($baseTimeLine as $baseSlot) {
            if ($baseSlot['start_time'] >= $startTime && $baseSlot['start_time'] < $endTime) {
                $colspan++;
            }
        }

        return $colspan ?: 1;
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
