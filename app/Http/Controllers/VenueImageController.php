<?php

namespace App\Http\Controllers;

use App\Models\VenueImage;
use App\Traits\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\IVenueImageService;
use App\Http\Requests\VenueImageRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


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
     */
    public function store(VenueImageRequest $request, string $venue_id): JsonResponse
    {
        $this->authorize('store', VenueImage::class);

        $data = $request->validated();
        $data['venue_id'] = $venue_id;

        $image = $this->venueImageService->store($data);

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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function updateThumbnail(VenueImageRequest $request, int $image_id): JsonResponse
    {
        $this->authorize('updateThumbnail', [VenueImage::class, $image_id]);

        $data = $request->only('is_thumbnail');

        $image = $this->venueImageService->updateThumbnail($image_id, $data);

        return $this->successResponse($image, 'Updated thumbnail status successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $image_id): JsonResponse
    {
        $this->authorize('delete', VenueImage::class);

        $success = $this->venueImageService->destroy($image_id);
        if (!$success) {
            return $this->errorResponse('Failed to delete venue image', 500);
        }
        return $this->successResponse(null, 'Venue Image Deleted Successfully');
    }
}
