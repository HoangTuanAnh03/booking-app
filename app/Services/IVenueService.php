<?php
namespace App\Services;

use App\Http\Requests\PaginatingDataVenueRequest;
use App\Http\Requests\VenueFormRequest;
use App\Models\Venue;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Interface IVenueService
 *
 * Defines the contract for managing venues, including
 * retrieving, creating, updating, and deleting venues.
 */
interface IVenueService
{
    /**
     * Get a paginated list of venues.
     *
     * @param PaginatingDataVenueRequest $request
     * @return mixed Paginated list of venues
     */
    public function show(PaginatingDataVenueRequest $request);

    public function showForOwner(PaginatingDataVenueRequest $request);
    /**
     * Find a venue by its ID.
     *
     * @param string $id Venue UUID
     * @return Venue|null Venue object or null if not found
     */
    public function findById(string $id): ?Venue;

    /**
     * Create and store a new venue.
     *
     * @return Venue The created venue
     */
    public function add(VenueFormRequest $request): Venue;

    /**
     * Update a venue by its ID.
     *
     * @param string $id Venue UUID
     * @return Venue|null The updated venue or null if not found
     */
    public function update(string $id, VenueFormRequest $request): ?Venue;

    /**
     * Update the status of a venue (admin only).
     *
     * @param string $id Venue UUID
     * @param array $data Validated status data
     * @return Venue|null The updated venue
     */
    public function updateStatus(string $id, array $data): ?Venue;

    /**
     * Delete a venue by its ID.
     *
     * @param string $id Venue UUID
     * @return Venue|null The deleted venue or null if not found
     */
    public function delete(string $id): ?Venue;

    // public function searchByFilter(PaginatingDataVenueRequest $request);

    /**
     * Get a collection of venues for the map.
     *
     * @return Collection
     */
    public function venueForMap(): Collection;

    /**
     * Get the detailed information of a specific venue by its ID.
     *
     * @param string $venueId
     * @return array
     */
    public function getVenueDetail(string $venueId): array;

    /**
     * Retrieves venues associated with a user ID.
     *
     * @param string $userId The user ID.
     * @return Collection The collection of venues.
     */
    public function getVenueByUid(string $userId): Collection;

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
