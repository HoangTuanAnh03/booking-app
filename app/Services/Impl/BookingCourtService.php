<?php

namespace App\Services\Impl;

use App\Http\Requests\BookingCourtRequest;
use App\Models\BookingCourt;
use App\Repository\IBookingCourtRepository;
use App\Services\IBookingCourtService;

class BookingCourtService implements IBookingCourtService
{
    private IBookingCourtRepository $repository;

    public function __construct(IBookingCourtRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(): array
    {
        return $this->repository->show();
    }

    public function findById(string $id): ?BookingCourt
    {
        return $this->repository->getById($id);
    }

    public function add(BookingCourtRequest $request): BookingCourt
    {
        $data = [
            'booking_id' => $request->get('booking_id'),
            'court_id' => $request->get('court_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'price' => $request->get('price'),
        ];
        return $this->repository->store($data);
    }

    public function update(string $id, BookingCourtRequest $request): ?BookingCourt
    {
        $data = [
            'booking_id' => $request->get('booking_id'),
            'court_id' => $request->get('court_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'price' => $request->get('price'),
        ];
        return $this->repository->update($data, $id);
    }

    public function delete(string $id): ?BookingCourt
    {
        return $this->repository->delete($id);
    }
}
