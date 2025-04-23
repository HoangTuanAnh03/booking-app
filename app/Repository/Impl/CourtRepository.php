<?php

namespace App\Repository\Impl;

use App\Exceptions\ErrorException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\CourtSpecialTimeRequest;
use App\Models\Court;
use App\Models\CourtSlot;
use App\Models\CourtSpecialTime;
use App\Models\FieldPrice;
use App\Repository\ICourtRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourtRepository implements ICourtRepository
{
    public function show()
    {
        return Court::all()->toArray();
    }

    public function getById(string $id)
    {
        return Court::where('court_id', $id)->first();
    }

    public function store(array $data)
    {
        return Court::create($data);
    }

    public function update(array $data, string $id)
    {
        $court = Court::where('court_id', $id)->first();
        if (!$court) return null;

        $court->update($data);
        return $court;
    }

    public function delete(string $id)
    {
        $court = Court::where('court_id', $id)->first();
        if (!$court) return null;

        $court->delete();
        return $court;
    }

    public function getOwnerId(string $courtId){
        return DB::table('courts')
            ->join('fields', 'courts.field_id', '=', 'fields.field_id')
            ->join('venues', 'fields.venue_id', '=', 'venues.venue_id')
            ->where('courts.court_id', $courtId)
            ->value('venues.owner_id');
    }

    /**
     * @throws UnauthorizedException
     */
    public function createSpecialTimes(CourtSpecialTimeRequest $request): array
    {
        return DB::transaction(function () use ($request) {
            $uid = $request->user()->uuid;
            $date = $request->date;
            $dayOfWeek = Carbon::parse($date)->format('l');
            $now = Carbon::now();
            $results = [];

            foreach ($request->courts as $courtData) {
                $courtId = $courtData['court_id'];

                if ($uid != $this->getOwnerId($courtId)) {
                    throw new UnauthorizedException("You are not allowed to create Court SpecialTimes in this court");
                }

                $rawSlots = collect($courtData['time_slots'])->sortBy('start_time')->values();
                $court = Court::with('field')->where('court_id', $courtId)->firstOrFail();
                $field = $court->field;

                // Gộp các time_slots liên tiếp
                $slotGroups = $this->groupContinuousSlots($rawSlots);

                foreach ($slotGroups as $slotGroup) {
                    $startTime = collect($slotGroup)->pluck('start_time')->min();
                    $endTime = collect($slotGroup)->pluck('end_time')->max();

                    // Kiểm tra slot bị khóa
                    $conflictSlots = CourtSlot::with(['bookingCourt.booking'])
                        ->where('court_id', $courtId)
                        ->where('date', $date)
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->where(function ($sub) use ($startTime, $endTime) {
                                $sub->where('start_time', '<', $endTime)
                                    ->where('end_time', '>', $startTime);
                            });
                        })
                        ->get();

                    $isLocked = false;
                    foreach ($conflictSlots as $slot) {
                        if ($slot->locked_by_owner) {
                            $isLocked = true;
                            break;
                        }
                        $booking = optional($slot->bookingCourt)->booking;
                        if ($booking) {
                            if ($booking->status === 'completed') {
                                $isLocked = true;
                                break;
                            }
                            if (($booking->status === 'pending' || $booking->status === 'confirmed') && $booking->created_at->diffInMinutes($now) < 30) {
                                $isLocked = true;
                                break;
                            }
                        }
                    }

                    if ($isLocked) {
                        throw new ErrorException("Time slot is locked or overlaps with existing booking");
                    }

                    // Tính tổng giá
                    $totalPrice = 0;
                    $min_rental = 0;

                    foreach ($slotGroup as $slotTime) {
                        $specialTime = CourtSpecialTime::where('court_id', $courtId)
                            ->where('date', $date)
                            ->where(function ($query) use ($slotTime) {
                                $query->where(function ($sub) use ($slotTime) {
                                    $sub->where('start_time', '=', $slotTime['start_time'])
                                        ->where('end_time', '=', $slotTime['end_time']);
                                });
                            })
                            ->first();
                        if ($specialTime) {
                            $totalPrice += $specialTime->price;
                            $min_rental += $specialTime->min_rental;
                            $specialTime->delete();
                            continue;
                        }

                        $fp = FieldPrice::where('field_id', $field->field_id)
                            ->where('day_of_week', $dayOfWeek)
                            ->where('start_time', '<=', $slotTime['start_time'])
                            ->where('end_time', '>=', $slotTime['end_time'])
                            ->first();
                        if ($fp) {
                            $totalPrice += $fp->price;
                            $min_rental += $fp->min_rental;
                        } else {
                            $totalPrice += $field->default_price;
                            $min_rental += 30;
                        }
                    }

                    CourtSpecialTime::create([
                        'court_id' => $courtId,
                        'date' => $date,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'price' => $totalPrice,
                        'min_rental' => $min_rental,
                    ]);

                    $results[] = [
                        'court_id' => $courtId,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => 'success',
                        'price' => $totalPrice,
                    ];
                }
            }

            return $results;
        });
    }

    // Gộp các slot liên tiếp nhau
    private function groupContinuousSlots($slots): array
    {
        $slots = collect($slots)->sortBy('start_time')->values();
        $groups = [];
        $currentGroup = [];

        foreach ($slots as $index => $slot) {
            $currentGroup[] = $slot;

            $next = $slots->get($index + 1);
            if (!$next) {
                $groups[] = $currentGroup;
                break;
            }

            if ($slot['end_time'] !== $next['start_time']) {
                $groups[] = $currentGroup;
                $currentGroup = [];
            }
        }

        return $groups;
    }
}
