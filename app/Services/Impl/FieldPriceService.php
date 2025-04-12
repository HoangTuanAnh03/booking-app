<?php

namespace App\Services\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Repository\IFieldOpeningHoursRepository;
use App\Repository\IFieldPriceRepository;
use App\Repository\IFieldRepository;
use App\Repository\IVenueRepository;
use App\Services\IFieldPriceService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\throwException;

class FieldPriceService implements IFieldPriceService
{
    private IFieldPriceRepository $fieldPriceRepository;
    private IFieldOpeningHoursRepository $fieldOpeningHoursRepository;

    private IFieldRepository $fieldRepository;

    private IVenueRepository $venueRepository;

    public function __construct(IFieldPriceRepository $fieldPriceRepository, IFieldOpeningHoursRepository $fieldOpeningHoursRepository, IFieldRepository $fieldRepository, IVenueRepository $venueRepository){
        $this->fieldPriceRepository = $fieldPriceRepository;
        $this->fieldOpeningHoursRepository = $fieldOpeningHoursRepository;
        $this->fieldRepository = $fieldRepository;
        $this->venueRepository = $venueRepository;
    }

    /**
     * @throws NotFoundException
     * @throws UnauthorizedException
     * @throws ErrorException
     */
    public function save($request, $fieldId): void
    {
        $field = $this->fieldRepository->getById($fieldId);
        if(!$field){
            throw new NotFoundException("Field not found");
        }
        $venue = $this->venueRepository->getById($field->venue_id);
        if(!$venue){
            throw new NotFoundException("Venue not found");
        }

        if($venue->owner_id != $request->user()->uuid){
            throw new UnauthorizedException("You are not authorized to access this page");
        }

        $fieldPrices = $request['field_prices'];
        $fieldPricesByDay = collect($fieldPrices)
            ->groupBy('day_of_week')
            ->map(function ($slots) {
                return $slots->sortBy('start_time')->values();
            });
        $this->validation($fieldPricesByDay, $fieldId);
        $fieldPricesByDay = $this->addGapsToFieldPricesByDay($fieldPricesByDay, $field->default_price, $fieldId);
        $this->fieldPriceRepository->saveAll($fieldPricesByDay, $fieldId);
    }

    /**
     * @throws ErrorException
     */
    private function validation($fieldPricesByDay, $fieldId): void
    {
        foreach ($fieldPricesByDay as $day => $slots) {
            $openingHour = $this->fieldOpeningHoursRepository->getByFieldIdAndDayOfWeek($fieldId, $day);
            if (!$openingHour) {
                throw new ErrorException("Sân không hoạt động vào ngày $day, vui lòng thêm Thời Gian Mở Cửa trước.");
            }

            for ($i = 0; $i < count($slots); $i++) {
                $current = $slots[$i];
                $duration = Carbon::parse($current['end_time'])->diffInMinutes(Carbon::parse($current['start_time']));
                if ($duration % $current['min_rental'] !== 0) {
                    $end = $current['end_time'];
                    $start = $current['start_time'];
                    $min_rental = $current['min_rental'];
                    throw new ErrorException("Khoảng thời gian $start -> $end trong $day phải là bội của $min_rental");
                }

                if ($i === 0 && Carbon::parse($current['start_time'])->diffInMinutes(Carbon::parse($openingHour->opening_time)) % 30 !== 0) {
                    $start = $current['start_time'];
                    throw new ErrorException("$start - của ngày $day không hợp lệ");
                }

                if ($i === count($slots) - 1 && Carbon::parse($openingHour->closing_time)->diffInMinutes(Carbon::parse($current['end_time'])) % 30 !== 0) {
                    $end = $current['end_time'];
                    throw new ErrorException("$end của ngày $day không hợp lệ");
                }

                if (isset($slots[$i + 1])) {
                    $next = $slots[$i + 1];
                    if (Carbon::parse($next['start_time'])->lt(Carbon::parse($current['end_time']))) {
                        throw new ErrorException("Lồng Khoảng Thời Gian");
                    }
                    $gap = Carbon::parse($next['start_time'])->diffInMinutes(Carbon::parse($current['end_time']));
                    if ($gap % 30 !== 0) {
                        throw new ErrorException("Thời gian trống phải là bội của 30 phút");
                    }
                }
            }
        }
    }

    private function addGapsToFieldPricesByDay($fieldPricesByDay, $defaultPrice, $fieldId): Collection
    {
        $updatedFieldPricesByDay = collect();

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $openingHour = $this->fieldOpeningHoursRepository->getByFieldIdAndDayOfWeek($fieldId, $day);
            if (!$openingHour) continue;

            $openingTime = Carbon::parse($openingHour->opening_time);
            $closingTime = Carbon::parse($openingHour->closing_time);

            $newSlots = [];

            if ($fieldPricesByDay->has($day)) {
                $slots = $fieldPricesByDay[$day]->sortBy('start_time')->values();
                $lastEndTime = $openingTime;

                foreach ($slots as $slot) {
                    $startTime = Carbon::parse($slot['start_time']);

                    if ($startTime->gt($lastEndTime)) {
                        $newSlots[] = [
                            'day_of_week' => $day,
                            'start_time' => $lastEndTime->format('H:i'),
                            'end_time' => $startTime->format('H:i'),
                            'price' => $defaultPrice,
                            'min_rental' => 30,
                        ];
                    }

                    $newSlots[] = $slot;
                    $lastEndTime = Carbon::parse($slot['end_time']);
                }

                if ($lastEndTime->lt($closingTime)) {
                    $newSlots[] = [
                        'day_of_week' => $day,
                        'start_time' => $lastEndTime->format('H:i'),
                        'end_time' => $closingTime->format('H:i'),
                        'price' => $defaultPrice,
                        'min_rental' => 30,
                    ];
                }
            } else {
                $newSlots[] = [
                    'day_of_week' => $day,
                    'start_time' => $openingTime->format('H:i'),
                    'end_time' => $closingTime->format('H:i'),
                    'price' => $defaultPrice,
                    'min_rental' => 30,
                ];
            }

            $updatedFieldPricesByDay[$day] = collect($newSlots);
        }

        return $updatedFieldPricesByDay;
    }

    public function get($fieldId, $dayOfWeek): array{
        return $this->fieldPriceRepository->get($fieldId, $dayOfWeek);
    }
}
