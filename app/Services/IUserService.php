<?php

namespace App\Services;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface IUserService
{
    /**
     * Get the authenticated user's information.
     *
     * @param Request $request
     * @return User
     */
    public function getCurrentUser(Request $request): User;

    /**
     * Retrieves all users.
     *
     * @return Collection The collection of users.
     */
    public function getUsers(): Collection;

    /**
     * Upgrades the role of a user.
     *
     * @param string $userId The user ID.
     * @return User The updated user object.
     */
    public function upRole(string $userId): User;
}
