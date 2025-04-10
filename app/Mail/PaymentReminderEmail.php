<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $ownerName;
    public string $venueName;

    public function __construct(string $ownerName, string $venueName)
    {
        $this->ownerName = $ownerName;
        $this->venueName = $venueName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Reminder Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.monthlyPayment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
