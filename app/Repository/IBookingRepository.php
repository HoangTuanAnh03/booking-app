<?php

namespace App\Repository;

use App\Models\Booking;
use App\Models\BookingCourt;
use App\Models\FieldPrice;

interface IBookingRepository
{
    /**
     * Finds a booking by ID.
     *
     * @param string $id The booking ID.
     * @return mixed The booking data or null if not found.
     */
    public function findById(string $id): mixed;

    /**
     * Creates a new booking.
     *
     * @param array $data The booking data.
     * @return Booking The created booking.
     */
    public function createBooking(array $data): Booking;

    /**
     * Creates a booking court entry.
     *
     * @param array $data The booking court data.
     * @return BookingCourt The created booking court object.
     */
    public function createBookingCourt(array $data): BookingCourt;

    /**
     * Finds the price for a field based on day and time.
     *
     * @param string $fieldId The field ID.
     * @param string $dayOfWeek The day of the week.
     * @param string $startTime The start time.
     * @param string $endTime The end time.
     * @return FieldPrice|null The field price or null if not found.
     */
    public function findFieldPrice(string $fieldId, string $dayOfWeek, string $startTime, string $endTime): ?FieldPrice;

    /**
     * Checks if a court slot overlaps with existing bookings.
     *
     * @param string $courtId The court ID.
     * @param string $startTime The start time.
     * @param string $endTime The end time.
     * @return bool True if there is an overlap, false otherwise.
     */
    public function checkCourtSlotOverlap(string $courtId, string $startTime, string $endTime): bool;

    /**
     * Retrieves booking statistics for a user.
     *
     * @param string $userId The user ID.
     * @param int $perPage Number of results per page.
     * @return mixed The user booking statistics.
     */
    public function getUserBookingStats(string $userId, int $perPage): mixed;

    /**
     * Retrieves booking statistics for an owner.
     *
     * @param array $fieldIds The array of field IDs.
     * @param int $perPage Number of results per page.
     * @return mixed The owner booking statistics.
     */
    public function getOwnerBookingStats(array $fieldIds, int $perPage): mixed;
}
