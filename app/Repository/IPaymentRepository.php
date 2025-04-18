<?php

namespace App\Repository;

use App\Models\Payment;

interface IPaymentRepository
{
    /**
     * Creates a new payment.
     *
     * @param array $data The payment data.
     * @return Payment The created payment object.
     */
    public function createPayment(array $data): Payment;

    /**
     * Updates a payment for a booking.
     *
     * @param mixed $bookingId The booking ID.
     * @param array $data The updated payment data.
     * @return Payment The updated payment object.
     */
    public function update($bookingId, array $data): Payment;
}
