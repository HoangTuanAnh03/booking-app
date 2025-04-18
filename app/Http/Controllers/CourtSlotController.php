<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourtSlotRequest;
use App\Services\ICourtSlotService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CourtSlotController extends Controller
{
    use ApiResponse;

    private ICourtSlotService $courtSlotService;

    public function __construct(ICourtSlotService $courtSlotService) {
        $this->courtSlotService = $courtSlotService;
    }
//
//    public function index(): JsonResponse {
//        return $this->successResponse($this->courtSlotService->show(), "List of Court Slot");
//    }
//
//    public function store(CourtSlotRequest $request): JsonResponse {
//        $this->authorize('create', Court::class);
//        return $this->successResponse($this->courtSlotService->add($request), "Saved Court Slot");
//    }
//
//    public function findById(string $id): JsonResponse {
//        return $this->successResponse($this->courtSlotService->findById($id), "Court Slot by ID");
//    }
//
//    public function update(string $id, CourtSlotRequest $request): JsonResponse {
//        $this->authorize('update', Court::class);
//        $data = $this->courtSlotService->update($id, $request);
//        if (!$data) {
//            return $this->errorResponse("Updated Court Slot Failed", 500);
//        }
//        return $this->successResponse($data, "Updated Court Slot by ID", 200);
//    }
//
//    public function delete(string $id): JsonResponse {
//        $this->authorize('delete', Court::class);
//        $data = $this->courtSlotService->delete($id);
//        if (!$data) {
//            return $this->errorResponse("Deleted Court Slot Failed", 500);
//        }
//        return $this->successResponse($data, "Deleted Court Slot by ID");
//    }

    /**
     * Locks a court slot by ID.
     *
     * @param Request $request The HTTP request object.
     * @param string $courtId The ID of the court slot to lock.
     * @return JsonResponse The response containing the lock result.
     */
    public function lock(Request $request, string $courtId): JsonResponse {
        return $this->successResponse($this->courtSlotService->lockById($request, $courtId));
    }
}
