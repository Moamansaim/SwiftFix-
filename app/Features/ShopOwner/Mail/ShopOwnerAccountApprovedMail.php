<?php

namespace App\Features\ShopOwner\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShopOwnerAccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $password,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('تمت الموافقة على طلب تسجيلك في SwiftFix')
            ->view('mail.shop-owner.account-approved');
    }
}