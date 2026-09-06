<?php

namespace App\Features\ShopOwner\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShopOwnerAccountRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $firstName,
        public string $lastName,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('نتيجة طلب التسجيل في SwiftFix')
            ->view('mail.shop-owner.account-rejected');
    }
}