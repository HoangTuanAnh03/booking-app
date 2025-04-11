<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Http\Request;

interface IUserService
{
    /**
     * Get the authenticated user's information.
     *
     * @param Request $request
     * @return User
     */
    public function getCurrentUser(Request $request): User;
}
