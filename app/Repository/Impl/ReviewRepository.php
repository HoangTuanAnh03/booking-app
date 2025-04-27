<?php

namespace App\Repository\Impl;

use App\Models\Review;
use App\Repository\IReviewRepository;
use App\Models\Venue;

class ReviewRepository implements IReviewRepository
{
    public function show(int $perPage, string $id)
    {

        return Review::where('venue_id', $id)
            ->with(['user' => function ($query) {
                $query->select('uuid', 'name');
            }])
            ->paginate($perPage);
    }

    public function getById(int $id)
    {
        return Review::where('review_id', $id)->first();
    }

    public function store(array $data)
    {
        $userId = $data['user_id'];
        $venueId = $data['venue_id'];

        $hasBookedVenue = Venue::join('fields', 'fields.venue_id', '=', 'venues.venue_id')
            ->join('courts', 'courts.field_id', '=', 'fields.field_id')
            ->join('booking_courts', 'booking_courts.court_id', '=', 'courts.court_id')
            ->join('booking', 'booking.booking_id', '=', 'booking_courts.booking_id')
            ->where('booking.user_id', $userId)
            ->where('venues.venue_id', $venueId)
            ->exists();

        $hasReviewVenue = Review::where('venue_id', $venueId)
            ->where('user_id', $userId)
            ->exists();

        if (!$hasBookedVenue) {
            return [
                'status' => 'error',
                'message' => 'You must book the venue before leaving a review.',
                'code' => 400
            ];
        }

        if ($hasReviewVenue) {
            return [
                'status' => 'error',
                'message' => 'You have already reviewed this venue.',
                'code' => 400
            ];
        }

        return [
            'status' => 'success',
            'data' => Review::create($data)
        ];
    }


    public function delete(int $id)
    {
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
