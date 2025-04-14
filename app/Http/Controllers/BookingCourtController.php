<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingCourtRequest;
use App\Services\IBookingCourtService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class BookingCourtController extends Controller
{
    use ApiResponse;

    private IBookingCourtService $bookingCourtService;

    public function __construct(IBookingCourtService $bookingCourtService)
    {
        $this->bookingCourtService = $bookingCourtService;
    }

    public function index(): JsonResponse
    {
        return $this->successResponse($this->bookingCourtService->show(), "List of Booking Courts");
    }

    public function store(BookingCourtRequest $request): JsonResponse
    {
        return $this->successResponse($this->bookingCourtService->add($request), "Saved Booking Court");
    }

    public function findById(int $id): JsonResponse
    {
        return $this->successResponse($this->bookingCourtService->findById($id), "Booking Court by ID");
    }

    public function update(int $id, BookingCourtRequest $request): JsonResponse
    {
        $data = $this->bookingCourtService->update($id, $request);
        if (!$data) {
            return $this->errorResponse("Updated Booking Court Failed", 500);
        }
        return $this->successResponse($data, "Updated Booking Court by ID", 200);
    }

    public function delete(int $id): JsonResponse
    {
        $data = $this->bookingCourtService->delete($id);
        if (!$data) {
            return $this->errorResponse("Deleted Booking Court Failed", 500);
        }
        return $this->successResponse($data, "Deleted Booking Court by ID");
    }
}
