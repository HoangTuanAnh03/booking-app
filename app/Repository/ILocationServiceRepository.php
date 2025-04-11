<?php


namespace App\Repository;

/**
 * Interface ILocationServiceRepository
 *
 * Defines methods for interacting with the LocationService data source.
 */
interface ILocationServiceRepository
{
    /**
     * Get a paginated list of LocationServices.
     *
     * @param int $perPage Number of LocationServices per page
     * @return mixed Paginated list of LocationServices
     */
    public function show(int $perPage, string $id);

    /**
     * Get a single LocationService by its ID.
     *
     * @param int $id LocationService service_id
     * @return mixed LocationService object or null if not found
     */
    public function getById(int $id);

    /**
     * Store a new LocationService in the database.
     *
     * @param array $data LocationService data to be saved
     * @return mixed Created LocationService
     */
    public function store(array $data);

    /**
     * Update an existing LocationService.
     *
     * @param array $data Updated LocationService data
     * @param int $id LocationService service_id
     * @return mixed Updated LocationService or null if not found
     */
    public function update(array $data, int $id);

    /**
     * Delete a LocationService by its ID.
     *
     * @param int $id LocationService service_id
     * @return mixed Deleted LocationService or null if not found
     */
    public function delete(int $id);
}
