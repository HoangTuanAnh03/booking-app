<?php
namespace App\Services;

use App\Http\Requests\PaginatingDataVenueRequest;
use App\Http\Requests\VenueFormRequest;
use App\Http\Requests\VenueRequest;
use App\Models\Venue;

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
     * @param int $perPage Number of items per page
     * @return mixed Paginated list of venues
     */
    public function show(PaginatingDataVenueRequest $request);

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
     * @param VenueRequest $request The validated request data
     * @return Venue The created venue
     */
    public function add(VenueFormRequest $request): Venue;

    /**
     * Update a venue by its ID.
     *
     * @param string $id Venue UUID
     * @param VenueRequest $request The validated request data
     * @return Venue|null The updated venue or null if not found
     */
    public function update(string $id, VenueFormRequest $request): ?Venue;

    /**
     * Update the status of a venue (admin only).
     *
     * @param string $id Venue UUID
     * @param array $data Validated status data
     * @return Venue The updated venue
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

}
