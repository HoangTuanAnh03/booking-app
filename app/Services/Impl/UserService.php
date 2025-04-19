<?php

namespace App\Services\Impl;

use App\Models\User;
use App\Repository\IUserRepository;
use App\Services\IUserService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class UserService implements IUserService
{
    use ApiResponse;

    private IUserRepository $UserRepository;

    public function __construct(IUserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    /**
     * @param Request $request
     * @return User
     */
    public function getCurrentUser(Request $request): User
    {
        return $request->user();
    }

    public function getUsers(): Collection{
        return $this->UserRepository->getUsers(['user', 'owner']);
    }

    public function upRole(string $userId): User{
        return $this->UserRepository->upRole($userId);
    }
}
