<?php

namespace App\Features\ShopOwner\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShopOwnerApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $email,
        public string $password,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'تمت الموافقة على طلبك - SwiftFix');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.shop-owner-approved');
    }
}