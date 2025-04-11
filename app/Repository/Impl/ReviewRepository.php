<?php
namespace App\Repository\Impl;

use App\Models\LocationService;
use App\Models\Review;
use App\Repository\ILocationServiceRepository;
use App\Repository\IReviewRepository;

class ReviewRepository implements IReviewRepository
{
    public function show(int $perPage, string $id) {

        return Review::where('venue_id', $id)->paginate($perPage);
    }

    public function getById(int $id) {
        return Review::where('review_id', $id)->first();
    }

    public function store(array $data) {
        return Review::create($data);
    }


    public function delete(int $id) {
        $Review = Review::where('review_id', $id)->first();
        if (!$Review) return null;

        $Review->delete();
        return $Review;
    }

    public function checkReviewOfUserInVenue(string $user_id, string $venue_id): bool
    {
        return Review::where('venue_id', $venue_id)
            ->where('user_id', $user_id)
            ->exists();
    }
}
