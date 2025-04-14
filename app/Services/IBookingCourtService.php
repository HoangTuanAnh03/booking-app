<?php
 namespace App\Services;
 
 use App\Http\Requests\BookingCourtRequest;
 use App\Models\BookingCourt;
 
 interface IBookingCourtService
 {
    public function show(): array;

    public function findById(string $id): ?BookingCourt;

    public function add(BookingCourtRequest $request): BookingCourt;

    public function update(string $id, BookingCourtRequest $request): ?BookingCourt;

    public function delete(string $id): ?BookingCourt;
 }