<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\IUserService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{

    use ApiResponse;

    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Get the authenticated user's information.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        // Get the authenticated user
        $user = $request->user();

        // Return the user data using UserResource
        return $this->successResponse(
            new UserResource($user),
            'Get my info successfully.'
        );
    }
}
