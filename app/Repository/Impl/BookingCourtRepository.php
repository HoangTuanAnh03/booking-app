<?php

namespace App\Repository\Impl;

use App\Models\BookingCourt;
use App\Repository\IBookingCourtRepository;

class BookingCourtRepository implements IBookingCourtRepository
{
    public function show()
    {
        return BookingCourt::all()->toArray();
    }

    public function getById(string $id)
    {
        return BookingCourt::where('booking_court_id', $id)->first();
    }

    public function store(array $data)
    {
        return BookingCourt::create($data);
    }

    public function update(array $data, string $id)
    {
        $bookingCourt = BookingCourt::where('booking_court_id', $id)->first();
        if (!$bookingCourt) return null;

        $bookingCourt->update($data);
        return $bookingCourt;
    }

    public function delete(string $id)
    {
        $bookingCourt = BookingCourt::where('booking_court_id', $id)->first();
        if (!$bookingCourt) return null;

        $bookingCourt->delete();
        return $bookingCourt;
    }
}
