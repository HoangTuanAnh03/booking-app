<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Services\IReviewService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    private IReviewService $reviewService;
    public function __construct(IReviewService $iReviewService) {
        $this->reviewService = $iReviewService;
    }

    public function index(Request $request):JsonResponse
    {
        $perPage = intval(request('per_page', 10));
        $perPage = max(1, min($perPage, 50));
        $id = $request['id'];
        return $this->successResponse(
            $this->reviewService->show($perPage,$id),
            "List of Reviews"
        );
    }
    public function findById(string $id): JsonResponse {
        return $this->successResponse(
            $this->reviewService->findById($id),
            "Review by ID"
        );
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request):JsonResponse
    {
        return $this->successResponse(
            $this->reviewService->add($request),
            "Saved Field"
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id, Request $request):JsonResponse
    {
        $data = $this->reviewService->delete($id, $request);
        if (!$data) {
            return $this->errorResponse("Deleted Review Failed", 500);
        }
        return $this->successResponse($data, "Deleted Review by ID");
    }
}
