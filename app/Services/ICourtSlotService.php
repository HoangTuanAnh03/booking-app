<?php

namespace App\Services;

use App\Http\Requests\CourtSlotRequest;
use App\Models\CourtSlot;
use Illuminate\Http\Request;

interface ICourtSlotService
{
    /**
     * Retrieves all court slots.
     *
     * @return array The list of court slots.
     */
    public function show(): array;

    /**
     * Finds a court slot by ID.
     *
     * @param string $id The court slot ID.
     * @return CourtSlot|null The court slot or null if not found.
     */
    public function findById(string $id): ?CourtSlot;

    /**
     * Creates a new court slot.
     *
     * @param CourtSlotRequest $request The court slot request object.
     * @return CourtSlot The created court slot.
     */
    public function add(CourtSlotRequest $request): CourtSlot;

    /**
     * Updates a court slot by ID.
     *
     * @param string $id The court slot ID.
     * @param CourtSlotRequest $request The court slot request object.
     * @return CourtSlot|null The updated court slot or null if not found.
     */
    public function update(string $id, CourtSlotRequest $request): ?CourtSlot;

    /**
     * Deletes a court slot by ID.
     *
     * @param string $id The court slot ID.
     * @return CourtSlot|null The deleted court slot or null if not found.
     */
    public function delete(string $id): ?CourtSlot;

    /**
     * Locks a court slot by ID.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The court slot ID.
     * @return CourtSlot|null The locked court slot or null if not found.
     */
    public function lockById(Request $request, string $id): ?CourtSlot;
}
