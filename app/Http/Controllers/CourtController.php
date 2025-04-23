<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourtRequest;
use App\Http\Requests\CourtSpecialTimeRequest;
use App\Services\ICourtService;
use App\Models\Court;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourtController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    private ICourtService $courtService;

    public function __construct(ICourtService $courtService) {
        $this->courtService = $courtService;
    }

    public function index(): JsonResponse {
        return $this->successResponse($this->courtService->show(), "List of Courts");
    }

    public function store(CourtRequest $request): JsonResponse {
        $this->authorize('create', Court::class);
        return $this->successResponse($this->courtService->add($request), "Saved Court");
    }

    public function findById(string $id): JsonResponse {
        return $this->successResponse($this->courtService->findById($id), "Court by ID");
    }

    public function update(string $id, CourtRequest $request): JsonResponse {
        $this->authorize('update', Court::class);
        $data = $this->courtService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Court Failed", 500);
        }
        return $this->successResponse($data, "Updated Court by ID", 200);
    }

    public function delete(string $id): JsonResponse {
        $this->authorize('delete', Court::class);
        $data = $this->courtService->delete($id);
        if (!$data) {
            return $this->errorResponse("Deleted Court Failed", 500);
        }
        return $this->successResponse($data, "Deleted Court by ID");
    }
    /**
     * Creates special times for a court.
     *
     * @param CourtSpecialTimeRequest $request The court special time request object.
     * @return JsonResponse The response with the created special times details.
     */
    public function createSpecialTimes(CourtSpecialTimeRequest $request): JsonResponse
    {
        return $this->successResponse($this->courtService->createSpecialTimes($request), "Created Court SpecialTimes");
    }
}
