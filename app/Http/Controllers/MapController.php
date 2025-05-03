<?php

namespace App\Http\Controllers;

use App\Services\IMapService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MapController extends Controller
{
    use ApiResponse;

    private IMapService $mapService;

    public function __construct(IMapService $mapService){
        $this->mapService = $mapService;
    }

    public function getLatLngByName(Request $request): \Illuminate\Http\JsonResponse
    {
        $address = $request->query('address');
        if (!$address) {
            return $this->errorResponse('address is required', 400);
        }
        try {
            $coords = $this->mapService->getLatLngByName($address);
            $data = [
                'lat' => $coords[0],
                'lng' => $coords[1],
            ];
            return $this->successResponse($data, "get lat lng by name");
        } catch (\Exception $e) {
            return $this->errorResponse("ERROR", 400);
        }
    }
}
