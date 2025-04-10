<?php


namespace App\Repository;

/**
 * Interface IReviewRepository
 *
 * Defines methods for interacting with the Review data source.
 */
interface IReviewRepository
{
    /**
     * Get a paginated list of Reviews.
     *
     * @param int $perPage Number of Reviews per page
     * @return mixed Paginated list of Reviews
     */
    public function show(int $perPage, string $id);

    /**
     * Get a single Review by its ID.
     *
     * @param int $id Review review_id
     * @return mixed Review object or null if not found
     */
    public function getById(int $id);

    /**
     * Store a new Review in the database.
     *
     * @param array $data Review data to be saved
     * @return mixed Created Review
     */
    public function store(array $data);


    /**
     * Delete a Review by its ID.
     *
     * @param int $id Review review_id
     * @return mixed Deleted Review or null if not found
     */
    public function delete(int $id);

    public function checkReviewOfUserInVenue(string $user_id, string $venue_id): bool;
}
