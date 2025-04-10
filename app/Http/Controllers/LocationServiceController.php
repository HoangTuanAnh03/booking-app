<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationServiceRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\ILocationServiceService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
/**
 * Class LocationServiceController
 *
 * This controller handles CRUD operations for Location Services.
 * It includes authorization checks for create, update, and delete actions.
 */
class LocationServiceController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    /**
     * The service that handles location service business logic.
     *
     * @var ILocationServiceService
     */
    private ILocationServiceService $locationService;

    /**
     * LocationServiceController constructor.
     *
     * @param ILocationServiceService $locationService
     */
    public function __construct(ILocationServiceService $locationService) {
        $this->locationService = $locationService;
    }

    /**
     * Get a paginated list of location services.
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse {
        $perPage = intval(request('per_page', 10));
        $perPage = max(1, min($perPage, 50));
        $id = $request['id'];
        return $this->successResponse(
            $this->locationService->show($perPage,$id),
            "List of Location Services"
        );
    }

    /**
     * Store a new location service.
     *
     * @param LocationServiceRequest $request
     * @return JsonResponse
     */
    public function store(LocationServiceRequest $request): JsonResponse {
        // Authorization can be added here if needed
        return $this->successResponse(
            $this->locationService->add($request),
            "Saved Location Service"
        );
    }

    /**
     * Find a location service by its ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function findById(int $id): JsonResponse {
        return $this->successResponse(
            $this->locationService->findById($id),
            "Location Service by ID"
        );
    }

    /**
     * Update an existing location service by ID.
     *
     * @param int $id
     * @param LocationServiceRequest $request
     * @return JsonResponse
     */
    public function update(int $id, LocationServiceRequest $request): JsonResponse {
        $data = $this->locationService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Location Service Failed", 500);
        }
        return $this->successResponse($data, "Updated Location Service by ID", 200);
    }

    /**
     * Delete a location service by ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id, Request $request): JsonResponse {
        $data = $this->locationService->delete($id, $request);
        if (!$data) {
            return $this->errorResponse("Deleted Location Service Failed", 500);
        }
        return $this->successResponse($data, "Deleted Location Service by ID");
    }
}
