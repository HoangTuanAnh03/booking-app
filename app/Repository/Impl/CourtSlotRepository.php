<?php

namespace App\Repository\Impl;

use App\Models\courtSlot;
use App\Repository\ICourtSlotRepository;

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
}
