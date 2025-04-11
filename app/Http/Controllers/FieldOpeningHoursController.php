<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldOpeningHoursRequest;
use App\Services\IFieldOpeningHoursService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class FieldOpeningHoursController extends Controller
{
    use ApiResponse;

    private IFieldOpeningHoursService $fieldOpeningHoursService;

    public function __construct(IFieldOpeningHoursService $fieldOpeningHoursService){
        $this->fieldOpeningHoursService = $fieldOpeningHoursService;
    }

    /**
     * Store opening hours for a field.
     *
     * @param FieldOpeningHoursRequest $request The request containing opening hours data.
     * @return JsonResponse Success response after saving.
     */
    public function store(FieldOpeningHoursRequest $request): JsonResponse
    {
        $this->fieldOpeningHoursService->save($request);
        return $this->successResponse(
            null,
            "Opening hours added"
        );
    }

    /**
     * Update opening hours for a specific field.
     *
     * @param FieldOpeningHoursRequest $request The request containing updated opening hours.
     * @param string $fieldId The ID of the field to update.
     * @return JsonResponse Success response after updating.
     */
    public function update(FieldOpeningHoursRequest $request, string $fieldId): JsonResponse
    {
        $this->fieldOpeningHoursService->save($request);
        return $this->successResponse(
            null,
            "Opening hours updated"
        );
    }

    /**
     * Get opening hours by field ID.
     *
     * @param string $fieldId The ID of the field.
     * @return JsonResponse Success response containing the opening hours.
     */
    public function showByFieldId(string $fieldId): JsonResponse
    {
        return $this->successResponse(
            $this->fieldOpeningHoursService->getByFieldId($fieldId),
            "Opening hours"
        );
    }

}
