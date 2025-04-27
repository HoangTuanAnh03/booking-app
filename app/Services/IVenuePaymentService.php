<?php

namespace App\Services;

use App\Http\Requests\VenuePaymentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

interface IVenuePaymentService
{
    /**
     * Process payment for a specific venue.
     *
     * @param VenuePaymentRequest $request The payment request data.
     * @param string $venue_id The ID of the venue.
     * @return JsonResponse The response after processing payment.
     */
    function makePayment(VenuePaymentRequest $request, string $venue_id): JsonResponse;

    /**
     * Handle the payment webhook callback.
     *
     * @param array $payload The webhook payload data.
     * @return void
     */
    public function handleWebhook(array $payload): void;

    /**
     * Get a list of unpaid venues by owner.
     *
     * @param VenuePaymentRequest $request The request containing the owner ID.
     * @return Collection|array List of unpaid venues.
     */
    public function getAllVenueByOwnerId(VenuePaymentRequest $request): Collection|array;

    /**
     * Send payment reminder emails to unpaid venues.
     *
     * @return void
     */
    public function paymentRemiderEmail(): void;

    /**
     * Lock venues that haven't completed payment.
     *
     * @return void
     */
    public function unpaidVenueLocking(): void;

    public function getTotalRevenue(): array;
}
