<?php

namespace App\Repository;

use App\Models\VenuePayment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface IVenuePaymentRepository
 *
 * Handles data access related to venue payments.
 */
interface IVenuePaymentRepository
{
    /**
     * Store a new venue payment record.
     *
     * @param array $data Payment data.
     * @return VenuePayment Created payment record.
     */
    public function store(array $data): VenuePayment;

    /**
     * Find payment info by venue ID.
     *
     * @param string $venueId Venue ID to search.
     * @return VenuePayment Payment record.
     */
    public function findByVenueId(string $venueId): VenuePayment;

    /**
     * Check if the venue has been paid for this month.
     *
     * @param string $venueId Venue ID.
     * @return bool True if already paid this month.
     */
    public function existsPaidThisMonth(string $venueId): bool;

    /**
     * Get all venues owned by a specific owner.
     *
     * @param string $ownerId Owner's ID.
     * @return Collection|array List of venues.
     */
    public function getAllVenueByOwnerId(string $ownerId): Collection|array;
}
