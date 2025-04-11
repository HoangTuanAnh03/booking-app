<?php

namespace App\Policies;

use App\Http\Requests\VenueFormRequest;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Auth\Access\Response;

class VenuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Venue $venue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'owner']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Venue $venue, VenueFormRequest $request): bool
    {
        if ($request && $request->has('status')) {
            return $user->role === 'admin';
        }
        if ($request->has('status')) {
            return false;
        }
        return $user->uuid === $venue->owner_id;
    }

    public function isActive(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Venue $venue): bool
    {
        return $user->uuid === $venue->owner_id || in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Venue $venue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Venue $venue): bool
    {
        return false;
    }
}
