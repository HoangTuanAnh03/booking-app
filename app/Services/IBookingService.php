<?php

namespace App\Services;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\CourtLockingRequest;
use App\Http\Requests\CourtSlotCheckingRequest;
use Illuminate\Http\Request;

interface IBookingService
{
    /**
     * Creates a new booking.
     *
     * @param BookingRequest $request The booking request object.
     * @return array The created booking details.
     */
    public function createBooking(BookingRequest $request): array;

    /**
     * Confirms a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param mixed $bookingId The booking ID.
     * @return array The confirmation details.
     */
    public function confirmBooking(Request $request, $bookingId): array;

    /**
     * Completes a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param mixed $bookingId The booking ID.
     * @return array The completion details.
     */
    public function completeBooking(Request $request, $bookingId): array;

    /**
     * Cancels a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param mixed $bookingId The booking ID.
     * @return string The cancellation message.
     */
    public function cancelBooking(Request $request, $bookingId): string;

    /**
     * Checks if a court slot is locked.
     *
     * @param CourtSlotCheckingRequest $request The court slot checking request object.
     * @return bool True if the slot is locked, false otherwise.
     */
    public function isLock(CourtSlotCheckingRequest $request): bool;

    /**
     * Locks a court slot.
     *
     * @param CourtLockingRequest $request The court locking request object.
     * @return array The lock details.
     */
    public function lock(CourtLockingRequest $request): array;

    /**
     * Retrieves user booking statistics.
     *
     * @param Request $request The HTTP request object.
     * @return array The user booking statistics.
     */
    public function getUserBookingStats(Request $request): array;

    /**
     * Retrieves owner booking statistics.
     *
     * @param Request $request The HTTP request object.
     * @return array The owner booking statistics.
     */
    public function getOwnerBookingStats(Request $request): array;

    /**
     * Generates a payment QR code for a booking.
     *
     * @param Request $request The HTTP request object.
     * @param mixed $bookingId The booking ID.
     * @return array The QR code details.
     */
    public function getPaymentQRCode(Request $request, $bookingId): array;

    public function getUserBookings(Request $request): array;

    public function stats(Request $request): array;

    public function getTop5VenuesByRevenue(Request $request);

    public function getTop5VenuesByBooking(Request $request);
}
