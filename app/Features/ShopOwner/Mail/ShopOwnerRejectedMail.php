<?php

namespace App\Features\ShopOwner\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShopOwnerRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public ?string $reason,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'تحديث بشأن طلبك - SwiftFix');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.shop-owner-rejected');
    }
}