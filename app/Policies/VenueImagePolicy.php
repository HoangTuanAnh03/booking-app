<?php

namespace App\Policies;

use App\Models\User;

class VenueImagePolicy
{
    public function store(User $user): bool
    {
        return $user->role === 'owner';
    }

    public function delete(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }
    public function updateThumbnail(User $user, string|int $image_id): bool
    {
        return $user->role === 'owner';
    }
}
