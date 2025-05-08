<?php

namespace App\Repository;

use App\Models\Venue;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Interface IVenueRepository
 *
 * Defines methods for interacting with the Venue data source.
 */
interface IVenueRepository
{
    /**
     * Get a paginated list of venues.
     *
     * @param int $perPage Number of venues per page
     * @return mixed Paginated list of venues
     */
    public function show(array $data);

    public function showForOwner(array $data);

    /**
     * Get a single venue by its ID.
     *
     * @param string $id Venue UUID
     * @return mixed Venue object or null if not found
     */
    public function getById(string $id);

    /**
     * Store a new venue in the database.
     *
     * @param array $data Venue data to be saved
     * @return mixed Created venue
     */
    public function store(array $data);

    /**
     * Update an existing venue.
     *
     * @param array $data Updated venue data
     * @param string $id Venue UUID
     * @return mixed Updated venue or null if not found
     */
    public function update(array $data, string $id);

    /**
     * Delete a venue by its ID.
     *
     * @param string $id Venue UUID
     * @return mixed Deleted venue or null if not found
     */
    public function delete(string $id);

    // public function searchByFilter(array $data);

    public function venueForMap(): Collection;

    public function getVenueDetail(string $venueId): array;

    /**
     * Retrieves venues associated with a user ID.
     *
     * @param string $userId The user ID.
     * @return Collection The collection of venues.
     */
    public function getVenueByUid(string $userId): Collection;

    /**
     * Get all venues with optional search and pagination.
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getVenues(?string $search = null, int $perPage = 10): LengthAwarePaginator;
    /**
     * Activates a venue by ID.
     *
     * @param string $venueId The venue ID.
     * @return Venue The activated venue object.
     */
    public function activateVenue(string $venueId): Venue;

    public function getVenueStas(): array;

    public function searchNearByLatLng($lat, $lng, $distance): Collection;
    public function searchNearByLatLngForHome($lat, $lng, $distance): Collection;
}
