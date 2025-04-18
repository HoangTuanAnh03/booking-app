<?php

namespace App\Repository\Impl;

use App\Models\Payment;
use App\Repository\IPaymentRepository;
use Illuminate\Support\Str;

class PaymentRepository implements IPaymentRepository
{

    public function createPayment(array $data): Payment
    {
        return Payment::create([
            'payment_id' => (string) Str::uuid(),
            'booking_id' => $data['booking_id'],
            'uid' => $data['uid'],
            'amount' => $data['amount'],
            'message' => $data['message'],
        ]);
    }

    public function update($bookingId, array $data): Payment{
        $payment = Payment::where('booking_id', $bookingId)->firstOrFail();
        $payment->update($data);
        return $payment;
    }
}
