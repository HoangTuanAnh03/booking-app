<?php

namespace App\Http\Controllers;

use App\Http\Requests\VenueImageRequest;
use App\Models\VenueImage;
use App\Services\IVenueImageService;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class VenueImageController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    protected IVenueImageService $venueImageService;

    public function __construct(IVenueImageService $venueImageService)
    {
        $this->venueImageService = $venueImageService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getImagesByVenue(string $venue_id): JsonResponse
    {
        // $this->authorize('viewAny', [VenueImage::class, $venue_id]);
        $images = $this->venueImageService->getAllByVenueId($venue_id);
        return $this->successResponse($images, 'List of Venue Images');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws AuthorizationException
     */
    public function store(VenueImageRequest $request, string $venue_id): JsonResponse
    {
        $this->authorize('store', [VenueImage::class, $venue_id]);

        $data = $request->validated();
        $data['venue_id'] = $venue_id;

        $image = $this->venueImageService->store($data);

        if (!$image) {
            return $this->errorResponse("Venue already has a {$data['type']} image.", 422);
        }

        return $this->successResponse($image, 'Venue Image Uploaded Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * @throws AuthorizationException
     */
    public function update(VenueImageRequest $request, int $image_id): JsonResponse
    {
        $this->authorize('update', [VenueImage::class, $image_id]);

        $data = $request->only('image_url');

        $image = $this->venueImageService->update($image_id, $data);

        return $this->successResponse($image, 'Updated type status successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(int $image_id): JsonResponse
    {
        $this->authorize('delete', [VenueImage::class, $image_id]);

        $success = $this->venueImageService->destroy($image_id);
        if (!$success) {
            return $this->errorResponse('Failed to delete venue image', 500);
        }
        return $this->successResponse(null, 'Venue Image Deleted Successfully');
    }
}
