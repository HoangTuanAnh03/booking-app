<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaginatingDataVenueRequest;
use App\Http\Requests\UpdateVenueStatusRequest;
use App\Http\Requests\VenueFormRequest;
use App\Models\Venue;
use App\Services\IMapService;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use App\Services\IVenueService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;


/**
 * Class VenueController
 *
 * This controller handles CRUD operations for Venues.
 * It includes authorization checks for create, update, and delete actions.
 */
class VenueController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    /**
     * The service that handles venue business logic.
     *
     * @var IVenueService
     */
    private IVenueService $venueService;

    private IMapService $mapService;

    /**
     * VenueController constructor.
     *
     * @param IVenueService $venueService
     * @param IMapService $mapService
     */
    public function __construct(IVenueService  $venueService, IMapService $mapService)
    {
        $this->venueService = $venueService;
        $this->mapService = $mapService;
    }

    /**
     * Get a paginated list of venues.
     *
     * @param PaginatingDataVenueRequest $request
     * @return JsonResponse
     */
    public function index(PaginatingDataVenueRequest $request): JsonResponse
    {
        return $this->successResponse(
            $this->venueService->show($request),
            "List of Venues"
        );
    }

    /**
     * Store a new venue.
     * Only authorized users (admin or owner) can perform this action.
     *
     * @param VenueFormRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(VenueFormRequest $request): JsonResponse
    {
        $this->authorize('create', Venue::class);
        return $this->successResponse($this->venueService->add($request), "Saved Venue");
    }

    /**
     * Find a venue by its ID.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function findById(string $id): JsonResponse
    {
        return $this->successResponse($this->venueService->findById($id), "Venue by ID");
    }

    /**
     * Update an existing venue by ID.
     * Only the venue owner can update it.
     *
     * @param string $id
     * @param VenueFormRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function update(string $id, VenueFormRequest $request): JsonResponse
    {
        $venue = $this->venueService->findById($id);
        $this->authorize('update', [$venue, $request]);
        $data = $this->venueService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Venue Failed", 500);
        }
        return $this->successResponse($data, "Updated Venue by id");
    }

    /**
     * Delete a venue by ID.
     * Only the venue owner can delete it.
     *
     * @param string $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function delete(string $id): JsonResponse
    {
        $venue = $this->venueService->findById($id);
        $this->authorize('delete', $venue);
        $data = $this->venueService->delete($id);
        if (!$data) {
            return $this->errorResponse("Deleted Venue Failed", 500);
        }
        return $this->successResponse($data, "Deleted Venue by id");
    }

    /**
     * @throws AuthorizationException
     */
    public function updateStatus(UpdateVenueStatusRequest $request, string $id): JsonResponse
    {
        $this->authorize('isActive', Venue::class);
        $data = $this->venueService->updateStatus($id, $request->validated());
        return $this->successResponse($data, "Deleted Venue by id");
    }

    public function venueForMap(): JsonResponse{
        $data = $this->venueService->venueForMap();
        return $this->successResponse($data, "List of Venues for map");
    }

    public function getVenueDetail(string $venueId): JsonResponse{
        $data = $this->venueService->getVenueDetail($venueId);
        return $this->successResponse($data, "Detail of Venue for map");
    }

    public function searchNearByLatLng(Request $request): JsonResponse
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $address = $request->input('address');

        if ((!$lat || !$lng) && $address) {
            try {
                [$lat, $lng] = $this->mapService->getLatLngByName($address);
            } catch (\Exception $e) {
                return $this->errorResponse("ERROR CONVERT LATLNG", 500);
            }
        }
        if (!$lat || !$lng) {
            return $this->errorResponse("Missing lat/lng or address", 400);
        }

        [$lat, $lng] = $this->mapService->convertLatLng([$lat, $lng]);

        return $this->successResponse($this->venueService->searchNearByLatLng($lat, $lng), "List of Venues");
    }
}
