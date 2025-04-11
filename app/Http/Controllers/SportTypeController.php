<?php

namespace App\Http\Controllers;

use App\Http\Requests\SportTypeRequest;
use App\Services\Impl\SportTypeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class SportTypeController extends Controller
{
    use ApiResponse;

    private SportTypeService $sportTypeService;

    public function __construct(SportTypeService $SportTypeService){
        $this->sportTypeService = $SportTypeService;
    }

    /**
     * Get all Sport Types.
     *
     * @return JsonResponse Returns a list of Sport Types.
     */
    public function index(): JsonResponse
    {
        return $this->successResponse($this->sportTypeService->show(), "List of Sport Types");
    }

    /**
     * Store a new Sport Type.
     *
     * @param SportTypeRequest $request The request containing Sport Type data.
     * @return JsonResponse Returns the created Sport Type.
     */
    public function store(SportTypeRequest $request): JsonResponse
    {
        return $this->successResponse($this->sportTypeService->add($request), "Saved Sport Type");
    }

    /**
     * Get a Sport Type by ID.
     *
     * @param int $id The ID of the Sport Type.
     * @return JsonResponse Returns the Sport Type data.
     */
    public function findById(int $id): JsonResponse
    {
        return $this->successResponse($this->sportTypeService->findById($id), "Sport Type by id");
    }

    /**
     * Update a Sport Type by ID.
     *
     * @param int $id The ID of the Sport Type.
     * @param SportTypeRequest $request The request containing updated data.
     * @return JsonResponse Returns the updated Sport Type.
     */
    public function update(int $id, SportTypeRequest $request): JsonResponse
    {
        $data = $this->sportTypeService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Sport Type Failed", 500);
        }
        return $this->successResponse($data, "Updated Sport Type by id");
    }

    /**
     * Delete a Sport Type by ID.
     *
     * @param int $id The ID of the Sport Type.
     * @return JsonResponse Returns a success message if deleted.
     */
    public function delete(int $id): JsonResponse
    {
        $data = $this->sportTypeService->delete($id);
        if (!$data) {
            return $this->errorResponse("Deleted SportType Failed", 500);
        }
        return $this->successResponse($data, "Deleted SportType by id");
    }
}
