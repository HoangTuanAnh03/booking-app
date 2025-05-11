<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Requests\CourtLockingRequest;
use App\Http\Requests\CourtSlotCheckingRequest;
use App\Services\IBookingService;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookingController extends Controller
{
    use ApiResponse;
    private IBookingService $bookingService;
    public function __construct(IBookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Creates a new booking.
     *
     * @param BookingRequest $request The booking request object.
     * @return JsonResponse The response with the created booking details.
     */
    public function store(BookingRequest $request): JsonResponse
    {
        $fieldId = $request->input('field_id');
        $lockKey = "booking_lock:field:$fieldId";

        try {
            return Cache::lock($lockKey, 10)->block(5, function () use ($request) {
                $result = $this->bookingService->createBooking($request);
                return $this->successResponse($result, "Booking created successfully");
            });
        }catch (LockTimeoutException $e) {
            return $this->errorResponse("Booking error", $e->getCode());
        }
//        return $this->successResponse($this->bookingService->createBooking($request), "Booking created successfully");
    }

    /**
     * Confirms a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The booking ID.
     * @return JsonResponse The response with confirmation details.
     */
    public function confirm(Request $request, string $id): JsonResponse{
        return $this->successResponse($this->bookingService->confirmBooking($request, $id), "Booking confirmed successfully");
    }

    /**
     * Completes a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The booking ID.
     * @return JsonResponse The response with completion details.
     */
    public function completeBooking(Request $request, string $id): JsonResponse{
        return $this->successResponse($this->bookingService->completeBooking($request, $id), "Booking completed successfully");
    }

    /**
     * Cancels a booking by ID.
     *
     * @param Request $request The HTTP request object.
     * @param string $id The booking ID.
     * @return JsonResponse The response with cancellation details.
     */
    public function cancelBooking(Request $request, string $id): JsonResponse{
        return $this->successResponse($this->bookingService->cancelBooking($request, $id), "Booking cancelled successfully");
    }

    /**
     * Checks if a court slot is locked.
     *
     * @param CourtSlotCheckingRequest $request The court slot checking request object.
     * @return JsonResponse The response with lock status.
     */
    public function isLock(CourtSlotCheckingRequest $request): JsonResponse{
        return $this->successResponse($this->bookingService->isLock($request), "Court slot checking successfully");
    }

    /**
     * Locks a court slot.
     *
     * @param CourtLockingRequest $request The court locking request object.
     * @return JsonResponse The response with lock details.
     */
    public function lock(CourtLockingRequest $request): JsonResponse
    {
        return $this->successResponse($this->bookingService->lock($request), "Court slot locking successfully");
    }

    /**
     * Retrieves user booking statistics.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The response with user booking stats.
     */
    public function getUserBookingStats(Request $request): JsonResponse{
        return $this->successResponse($this->bookingService->getUserBookingStats($request), "User booking stats successfully");
    }

    /**
     * Retrieves owner booking statistics.
     *
     * @param Request $request The HTTP request object.
     * @return JsonResponse The response with owner booking stats.
     */
    public function getOwnerBookingStats(Request $request): JsonResponse{
        return $this->successResponse($this->bookingService->getOwnerBookingStats($request), "Owner booking stats successfully");
    }

    /**
     * Generates a payment QR code for a booking.
     *
     * @param Request $request The HTTP request object.
     * @param string $bookingId The booking ID.
     * @return JsonResponse The response with the QR code details.
     */
    public function getPaymentQRCode(Request $request, string $bookingId): JsonResponse{
        return $this->successResponse($this->bookingService->getPaymentQRCode($request, $bookingId), "Payment QR code successfully");
    }

    public function getUserBookings(Request $request): JsonResponse
    {
        return $this->successResponse($this->bookingService->getUserBookings($request), "User booking list successfully");
    }
}
