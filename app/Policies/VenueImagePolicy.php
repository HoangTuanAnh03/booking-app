<?php

namespace App\Policies;

use App\Models\User;
use App\Repository\Impl\VenueImageRepository;
use App\Repository\Impl\VenueRepository;

class VenueImagePolicy
{
    public function __construct(
        private VenueRepository $venueRepository,
        private VenueImageRepository $venueImageRepository
    ) {}

    public function store(User $user, string $venue_id): bool
    {
        return $this->isOwnerOfVenue($user, $venue_id);
    }

    public function delete(User $user, string|int $image_id): bool
    {
        $venue_id = $this->getVenueIdFromImage($image_id);
        return $venue_id && in_array($user->role, ['admin', 'owner']) && $this->isOwnerOfVenue($user, $venue_id);
    }

    public function update(User $user, string|int $image_id): bool
    {
        $venue_id = $this->getVenueIdFromImage($image_id);
        return $venue_id && $user->role === 'owner' && $this->isOwnerOfVenue($user, $venue_id);
    }

    private function getVenueIdFromImage(string|int $image_id): ?string
    {
        $data = $this->venueImageRepository->getById($image_id);

        if (!$data) {
            return null;
        }

        return $data->venue_id;
    }

    private function isOwnerOfVenue(User $user, string $venue_id): bool
    {
        $ownerId = $this->venueRepository->getById($venue_id)->owner_id;
        return $user->uuid === $ownerId;
    }
}
