<?php

namespace App\Http\Controllers;

use App\Services\IBookingService;
use App\Services\IFieldService;
use App\Services\IVenueService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    use ApiResponse;

    private IBookingService $bookingService;
    private IVenueService $venueService;
    private IFieldService $fieldService;

    public function __construct(IBookingService $bookingService, IVenueService $venueService, IFieldService $fieldService)
    {
        $this->bookingService = $bookingService;
        $this->venueService = $venueService;
        $this->fieldService = $fieldService;
    }

    public function dashboard(Request $request): JsonResponse
    {
        $fields = $this->fieldService->getTotalField($request);
        $totalVenues = $this->venueService->countVenuesByOwner($request);
        $activeVenues = $this->venueService->countActiveVenuesByOwner($request);
        $topVenues = $this->bookingService->getTop5VenuesByRevenue($request);
        $mostBooked = $this->bookingService->getTop5VenuesByBooking($request);

        $result = [
            'total_venues' => $totalVenues,
            'active_venues' => $activeVenues,
            'fields' => [
                'active' => (int) ($fields->active_fields ?? 0),
                'inactive' => (int) ($fields->inactive_fields ?? 0),
            ],
            'top_5_venues_by_revenue' => collect($topVenues)->map(function ($venue) {
                return [
                    'venue_name' => $venue->venue_name,
                    'revenue' => number_format($venue->revenue, 2),
                ];
            }),
            'top_5_booked_venue' => $mostBooked ? collect($mostBooked)->map(function ($venue) {
                return [
                    'venue_name' => $venue->venue_name,
                    'booking_count' => $venue->booking_count,
                ];
            }) : [],
        ];
        return $this->successResponse($result, "Dashboard successfully");
    }
}
