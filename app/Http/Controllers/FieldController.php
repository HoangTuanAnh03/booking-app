<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldRequest;
use App\Services\IFieldService;
use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    use ApiResponse;
    use AuthorizesRequests;

    private IFieldService $fieldService;
    /**
     * Display a listing of the resource.
     */
    public function __construct(IFieldService $fieldService) {
        $this->fieldService = $fieldService;
    }

    public function index(Request $request):JsonResponse
    {
        //
        $perPage = intval(request('per_page', 10));
        $perPage = max(1, min($perPage, 50));
        $id = $request['id'];
        return $this->successResponse(
            $this->fieldService->show($perPage,$id),
            "List of Field"
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FieldRequest $request):JsonResponse
    {
        //
        return $this->successResponse(
            $this->fieldService->add($request),
            "Saved Field"
        );
    }

    public function findById(string $id): JsonResponse {
        return $this->successResponse(
            $this->fieldService->findById($id),
            "Field by ID"
        );
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, FieldRequest $request):JsonResponse
    {
        //
        $data = $this->fieldService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Field Failed", 500);
        }
        return $this->successResponse($data, "Updated Field by ID", 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id, Request $request):JsonResponse
    {
        //
        $data = $this->fieldService->delete($id, $request);
        if (!$data) {
            return $this->errorResponse("Deleted Field Failed", 500);
        }
        return $this->successResponse($data, "Deleted Field by ID");
    }
}
