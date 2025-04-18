<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PaymentNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;
    public string $customerName;
    public string $customerPhone;
    public string $fieldName;
    public string $venueName;
    public string $price;
    public string $message_data;
    public string $date;
    public Collection $grouped;

    /**
     * @param string $customerName
     * @param string $customerPhone
     * @param string $fieldName
     * @param string $venueName
     * @param string $price
     * @param string $message_data
     * @param string $date
     * @param Collection $grouped
     */
    public function __construct(string $customerName, string $customerPhone, string $fieldName, string $venueName, string $price, string $message_data, string $date, Collection $grouped)
    {
        $this->customerName = $customerName;
        $this->customerPhone = $customerPhone;
        $this->fieldName = $fieldName;
        $this->venueName = $venueName;
        $this->price = $price;
        $this->message_data = $message_data;
        $this->date = Carbon::createFromFormat('Y-m-d', $date)->format('d/m/Y');
        $this->grouped = $grouped;
    }


    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thanh Toán Thuê Sân',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.bookingNoti',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
