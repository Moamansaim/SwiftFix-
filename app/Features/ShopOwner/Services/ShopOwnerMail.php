<?php

namespace App\Features\ShopOwner\Services;

use App\Features\ShopOwner\Mail\ShopOwnerAccountApprovedMail;
use App\Features\ShopOwner\Mail\ShopOwnerAccountRejectedMail;
use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Support\Facades\Mail;

class ShopOwnerMail
{
    /**
     * Send account approval email.
     */
    public function sendAccountApproval(
        ShopOwnerVerification $verification,
        string $password
    ): void {
        Mail::to($verification->email)->send(
            new ShopOwnerAccountApprovedMail(
                $verification->first_name,
                $verification->last_name,
                $verification->email,
                $password
            )
        );
    }

    /**
     * Send account rejection email.
     */
    public function sendAccountRejection(
        ShopOwnerVerification $verification
    ): void {
        Mail::to($verification->email)->send(
            new ShopOwnerAccountRejectedMail(
                $verification->first_name,
                $verification->last_name
            )
        );
    }
}