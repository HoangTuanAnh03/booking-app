<?php

namespace App\Repository;

use Illuminate\Support\Arr;

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

}
