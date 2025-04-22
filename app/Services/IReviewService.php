<?php
namespace App\Services;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;

/**
 * Interface IReviewService
 *
 * Defines the contract for managing Reviews, including
 * retrieving, creating, updating, and deleting Reviews.
 */
interface IReviewService
{
    /**
     * Get a paginated list of Reviews.
     *
     * @param int $perPage Number of items per page
     * @return mixed Paginated list of Reviews
     */
    public function show(int $perPage, string $id);

    /**
     * Find a Review by its ID.
     *
     * @param int $id Review review_id
     * @return Review|null Review object or null if not found
     */
    public function findById(int $id): ?Review;

    /**
     * Create and store a new Review.
     *
     * @param ReviewRequest $request The validated request data
     * @return array The created Review
     */
    public function add(ReviewRequest $request): array;


    /**
     * Delete a Review by its ID.
     *
     * @param int $id Review review_id
     * @return Review|null The deleted Review or null if not found
     */
    public function delete(int $id, Request  $request): ?Review;
}
