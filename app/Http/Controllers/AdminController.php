<?php

namespace App\Http\Controllers;

use App\Repository\IVenueRepository;
use App\Services\IFieldService;
use App\Services\IUserService;
use App\Services\IVenuePaymentService;
use App\Services\IVenueService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    use ApiResponse;

    private IUserService $userService;
    private IVenueService $venueService;

    private IVenuePaymentService $paymentService;
    private IFieldService $fieldService;

    public function __construct(IUserService $userService, IVenueService $venueService, IVenuePaymentService $paymentService, IFieldService $fieldService)
    {
        $this->userService = $userService;
        $this->venueService = $venueService;
        $this->paymentService = $paymentService;
        $this->fieldService = $fieldService;
    }

    /**
     * Retrieves all users.
     *
     * @return JsonResponse The response containing the list of users.
     */
    public function getUsers(): JsonResponse
    {
        return $this->successResponse(
            $this->userService->getUsers(),
            'Get users successfully.'
        );
    }

    /**
     * Upgrades the role of a user.
     *
     * @param string $userId The user ID.
     * @return JsonResponse The response with the updated user details.
     */
    public function upRole(string $userId): JsonResponse
    {
        return $this->successResponse($this->userService->upRole($userId), "Role successfully updated.");
    }

    /**
     * Retrieves venues associated with a user ID.
     *
     * @param string $userId The user ID.
     * @return JsonResponse The response containing the collection of venues.
     */
    public function getVenueByUid(string $userId): JsonResponse
    {
        return $this->successResponse($this->venueService->getVenueByUid($userId), "Get Venues successfully");
    }

    public function getVenues(Request $request): JsonResponse
    {
        $search = $request->input('search');
        return $this->successResponse($this->venueService->getVenues($search, 10));
    }

    public function activateVenue(string $venueId): JsonResponse
    {
        return $this->successResponse($this->venueService->activateVenue($venueId));
    }

    public function dashboard(): JsonResponse
    {
        $result = [
            'totalRevenue' => $this->paymentService->getTotalRevenue(),
            'venue' => $this->venueService->getVenueStas(),
            'sport_type' => $this->fieldService->getFieldStas(),
        ];
        return $this->successResponse($result);
    }
}
