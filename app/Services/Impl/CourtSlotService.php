<?php

namespace App\Services\Impl;

use App\Http\Requests\CourtSlotRequest;
use App\Models\CourtSlot;
use App\Repository\ICourtSlotRepository;
use App\Services\ICourtSlotService;
use Illuminate\Support\Str;

class CourtSlotService implements ICourtSlotService
{
    private ICourtSlotRepository $repository;

    public function __construct(ICourtSlotRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show(): array
    {
        return $this->repository->show();
    }

    public function findById(string $id): ?CourtSlot
    {
        return $this->repository->getById($id);
    }

    public function add(CourtSlotRequest $request): CourtSlot
    {
        $data = [
            'slot_id' => Str::uuid(),
            'court_id' => $request->get('court_id'),
            'booking_court_id' => $request->get('booking_court_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'is_looked' => $request->get('is_looked'),
            'locked_by_owner' => $request->get('locked_by_owner'),
        ];
        return $this->repository->store($data);
    }

    public function update(string $id, CourtSlotRequest $request): ?CourtSlot
    {
        $data = [
            'court_id' => $request->get('court_id'),
            'booking_court_id' => $request->get('booking_court_id'),
            'start_time' => $request->get('start_time'),
            'end_time' => $request->get('end_time'),
            'is_looked' => $request->get('is_looked'),
            'locked_by_owner' => $request->get('locked_by_owner'),
        ];
        return $this->repository->update($data, $id);
    }

    public function delete(string $id): ?CourtSlot
    {
        return $this->repository->delete($id);
    }
}
