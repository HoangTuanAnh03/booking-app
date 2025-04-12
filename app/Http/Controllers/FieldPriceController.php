<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldPriceRequest;
use App\Repository\Impl\FieldPriceRepository;
use App\Services\IFieldOpeningHoursService;
use App\Services\IFieldPriceService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FieldPriceController extends Controller
{
    use ApiResponse;

    private IFieldPriceService $fieldPriceService;

    public function __construct(IFieldPriceService $fieldPriceService){
        $this->fieldPriceService = $fieldPriceService;
    }

    /**
     * Save the field price for a specific field.
     *
     * @param FieldPriceRequest $request
     * @param string $fieldId
     * @return JsonResponse
     */
    public function save(FieldPriceRequest $request, string $fieldId): JsonResponse
    {
        $this->fieldPriceService->save($request, $fieldId);

        return $this->successResponse(
            null,
            "Field Price added"
        );
    }

    /**
     * Get the field prices for a specific field on a specific day of the week.
     *
     * @param string $fieldId
     * @param Request $request
     * @return JsonResponse
     */
    public function get(string $fieldId, Request $request): JsonResponse
    {
        $dayOfWeek = $request->query('day');
        $fieldPrices = $this->fieldPriceService->get($fieldId, $dayOfWeek);
        return $this->successResponse($fieldPrices, "List of field price");
    }

}
