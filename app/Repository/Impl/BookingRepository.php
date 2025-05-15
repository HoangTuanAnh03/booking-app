<?php

namespace App\Repository\Impl;

use App\Models\Booking;
use App\Models\bookingCourt;
use App\Models\FieldPrice;
use App\Repository\IBookingRepository;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class BookingRepository implements IBookingRepository
{
    public function findById($id): mixed
    {
        return Booking::where("booking_id", $id)->first();
    }
    public function createBooking(array $data): Booking
    {
        $booking_id = Str::uuid();
        $booking = Booking::create([
            'booking_id' => $booking_id,
            'field_id' => $data['field_id'],
            'user_id' => $data['user_id'],
            'total_price' => $data['total_price'],
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'status' => $data['status'] ?? 'pending',
            'booking_date' => $data['booking_date'],
        ]);
        $booking->refresh();
        return $booking;
    }

    public function createBookingCourt(array $data): BookingCourt
    {
        return BookingCourt::create([
            'booking_id' => $data['booking_id'],
            'court_id' => $data['court_id'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'price' => $data['price'],
        ]);
    }

    public function findFieldPrice(string $fieldId, string $dayOfWeek, string $startTime, string $endTime): ?FieldPrice
    {
        return FieldPrice::where('field_id', $fieldId)
            ->where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $startTime)
            ->where('end_time', '>=', $endTime)
            ->first();
    }

    public function checkCourtSlotOverlap(string $courtId, string $startTime, string $endTime): bool
    {
        return BookingCourt::where('court_id', $courtId)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();
    }

    public function getUserBookingStats(string $userId, int $perPage): mixed
    {
        return Booking::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function getOwnerBookingStats(array $fieldIds, int $perPage): mixed
    {
        return Booking::whereIn('field_id', $fieldIds)
            ->with(['bookingCourts' => function ($query) {
                $query->select('booking_court_id', 'booking_id', 'court_id', 'start_time', 'end_time', 'price');
            }])
            ->select('booking_id', 'field_id', 'user_id', 'total_price', 'customer_name', 'customer_phone', 'status', 'booking_date', 'created_at', 'order_id')
            ->orderByRaw("
                CASE
                    WHEN status = 'confirmed' AND created_at >= ? THEN 1
                    ELSE 2
                END ASC,
                created_at DESC
            ", [now()->subMinutes(30)])
            ->paginate($perPage);
    }
}
