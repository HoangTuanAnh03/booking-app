<?php

namespace App\Services;

use App\Http\Requests\CourtSlotRequest;
use App\Models\CourtSlot;

interface ICourtSlotService
{
    public function show(): array;

    public function findById(string $id): ?CourtSlot;

    public function add(CourtSlotRequest $request): CourtSlot;

    public function update(string $id, CourtSlotRequest $request): ?CourtSlot;

    public function delete(string $id): ?CourtSlot;
}
