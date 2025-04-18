<?php

namespace App\Repository\Impl;

use App\Exceptions\ErrorException;
use App\Models\courtSlot;
use App\Repository\ICourtSlotRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CourtSlotRepository implements ICourtSlotRepository
{
    public function show()
    {
        return CourtSlot::all()->toArray();
    }

    public function getById(string $id)
    {
        return CourtSlot::where('slot_id', $id)->first();
    }

    public function store(array $data)
    {
        return CourtSlot::create($data);
    }

    public function update(array $data, string $id)
    {
        $courtSlot = CourtSlot::where('slot_id', $id)->first();
        if (!$courtSlot) return null;

        $courtSlot->update($data);
        return $courtSlot;
    }

    public function delete(string $id)
    {
        $courtSlot = CourtSlot::where('slot_id', $id)->first();
        if (!$courtSlot) return null;

        $courtSlot->delete();
        return $courtSlot;
    }

    /**
     * @throws ErrorException
     */
    public function createCourtSlot(array $data): void
    {
        try {
            CourtSlot::create([
                'slot_id' => (string)Str::uuid(),
                'court_id' => $data['court_id'],
                'booking_court_id' => $data['booking_court_id'],
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'date' => $data['date'],
                'is_looked' => $data['is_looked'] ?? false,
                'locked_by_owner' => $data['locked_by_owner'] ?? false,
            ]);
        } catch (\Exception $e) {
            throw new ErrorException("Save Court Slot Failed $e");
        }
    }

    public function checkCourtSlotLock(array $data): bool
    {
        $thirtyMinutesAgo = now()->subMinutes(30);
        $courtId = $data['court_id'];
        $bookingDate = $data['booking_date'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];
        $baseConditions = [
            ['court_slots.court_id', '=', $courtId],
            ['court_slots.date', '=', $bookingDate],
            ['court_slots.start_time', '<', $endTime],
            ['court_slots.end_time', '>', $startTime],
        ];

        // Điều kiện 1: Khóa bởi chủ sân
        $lockedByOwner = CourtSlot::query()
            ->where($baseConditions)
            ->where('court_slots.locked_by_owner', true)
            ->exists();

        if ($lockedByOwner) {
            return true;
        }

        // Tạo join
        $joinedBaseQuery = CourtSlot::query()
            ->join('booking_courts', 'court_slots.booking_court_id', '=', 'booking_courts.booking_court_id')
            ->join('booking', 'booking_courts.booking_id', '=', 'booking.booking_id')
            ->where($baseConditions);

        // Điều kiện 2: Booking đã hoàn tất
        $completedBooking = (clone $joinedBaseQuery)
            ->where('booking.status', 'completed')
            ->exists();

        if ($completedBooking) {
            return true;
        }

        // Điều kiện 3: Được "looked" gần đây với trạng thái 'pending' hoặc 'confirmed'
        return (clone $joinedBaseQuery)
            ->where('court_slots.is_looked', true)
            ->whereIn('booking.status', ['pending', 'confirmed'])
            ->where('court_slots.created_at', '>=', $thirtyMinutesAgo)
            ->exists();
    }

    public function deleteCourtSlotsByBookingId(string $bookingId): void
    {
        DB::transaction(function () use ($bookingId) {
            try {
                $deletedCount = CourtSlot::whereIn('booking_court_id', function ($query) use ($bookingId) {
                    $query->select('booking_court_id')
                        ->from('booking_courts')
                        ->where('booking_id', $bookingId);
                });
                $deletedCount->delete();
            } catch (\Exception $e) {
                throw new \Exception("Failed to delete court slots for booking {$bookingId}: {$e->getMessage()}");
            }
        });
    }

    public function courtSlotExists(string $courtId, string $startTime, string $endTime, string $date): bool
    {
        return CourtSlot::where('court_id', $courtId)
            ->where('date', $date)
            ->where('start_time', $startTime)
            ->where('end_time', $endTime)
            ->exists();
    }
}
