<?php

namespace App\Repository;

use App\Models\CourtSlot;

interface ICourtSlotRepository
{
    public function show();
    public function getById(string $id);
    public function store(array $data);
    public function update(array $data, string $id);
    public function delete(string $id);
    /**
     * Creates a new court slot.
     *
     * @param array $data The court slot data.
     * @return void
     */
    public function createCourtSlot(array $data): void;

    /**
     * Checks if a court slot is locked.
     *
     * @param array $data The court slot data.
     * @return bool True if the slot is locked, false otherwise.
     */
    public function checkCourtSlotLock(array $data): bool;

    /**
     * Deletes court slots by booking ID.
     *
     * @param string $bookingId The booking ID.
     * @return void
     */
    public function deleteCourtSlotsByBookingId(string $bookingId): void;

    /**
     * Checks if a court slot exists for a given court, time, and date.
     *
     * @param string $courtId The court ID.
     * @param string $startTime The start time.
     * @param string $endTime The end time.
     * @param string $date The date.
     * @return bool True if the slot exists, false otherwise.
     */
    public function courtSlotExists(string $courtId, string $startTime, string $endTime, string $date): bool;
}
