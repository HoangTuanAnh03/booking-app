<?php

namespace App\Repository;

use App\Models\BookingCourt;

interface IBookingCourtRepository
{
    public function show();
    public function getById(string $id);
    public function getByBookingId(string $bookingId);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
}
