<?php
namespace App\Http\Controllers;
use App\Services\IVenuePaymentService;
use App\Traits\ApiResponse;
use App\Http\Requests\VenuePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class VenuePaymentController extends Controller
{
    use ApiResponse;
    private IVenuePaymentService $venuePaymentService;

    /**
     * VenuePaymentController constructor.
     *
     * @param IVenuePaymentService $venuePaymentService
     */
    public function __construct(IVenuePaymentService $venuePaymentService)
    {
        $this->venuePaymentService = $venuePaymentService;
    }

    /**
     * Create a new payment for a venue.
     *
     * @param VenuePaymentRequest $request Payment request with user info.
     * @param string $venue_id ID of the venue to pay for.
     * @return JsonResponse JSON response with payment link or error.
     */
    public function make(VenuePaymentRequest $request, string $venue_id): JsonResponse
    {
        $data = $this->venuePaymentService->makePayment($request, $venue_id);
        return $this->successResponse($data, "Updated Venue by id");
    }

    /**
     * Handle PayOS webhook callback.
     *
     * @param Request $request Incoming webhook request.
     * @return JsonResponse JSON response to acknowledge receipt.
     */
    public function handle(Request $request): JsonResponse
    {
        $this->venuePaymentService->handleWebhook($request->all());

        return response()->json(['received' => true]);
    }

    /**
     * Get all venues by owner ID (from auth user).
     *
     * @param VenuePaymentRequest $request Request containing authenticated user.
     * @return JsonResponse JSON response with list of venues.
     */
    public function getAllVenueByOwnerId(VenuePaymentRequest $request): JsonResponse
    {
        return $this->successResponse($this->venuePaymentService->getAllVenueByOwnerId($request));
    }

    /**
     * Sends payment reminder emails for unpaid venues.
     *
     * @param Request $request The incoming HTTP request.
     * @return JsonResponse JSON response indicating if the request was received.
     *
     * @throws JsonResponse 401 Unauthorized if 'X-PYTHON' header is missing.
     */
    public function paymentReminderEmail(Request $request): JsonResponse {
        if ($res = $this->rejectIfNotPython($request)) return $res;
        $this->venuePaymentService->paymentRemiderEmail();
        return response()->json(['received' => true]);
    }

    /**
     * Locks venues that have unpaid dues.
     *
     * @param Request $request The incoming HTTP request.
     * @return JsonResponse JSON response indicating if the request was received.
     *
     * @throws JsonResponse 401 Unauthorized if 'X-PYTHON' header is missing.
     */
    public function unpaidVenueLocking(Request $request): JsonResponse {
        if ($res = $this->rejectIfNotPython($request)) return $res;
        $this->venuePaymentService->unpaidVenueLocking();
        return response()->json(['received' => true]);
    }

    private function rejectIfNotPython(Request $request): JsonResponse|null {
        if (!$request->header('X-PYTHON')) {
            return response()->json(['received' => false, 'message' => "DON'T USE API"], 401);
        }
        return null;
    }
}
