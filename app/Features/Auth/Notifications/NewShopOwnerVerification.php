<?php

namespace App\Features\Auth\Notifications;

use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewShopOwnerVerification extends Notification
{
    use Queueable;

    public function __construct(
        public ShopOwnerVerification $verification
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
            'broadcast',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'طلب تسجيل ورشة جديد',

            'message' => 'تم إرسال طلب تسجيل جديد من '
                . $this->verification->first_name . ' '
                . $this->verification->last_name,

            'verification_id' => $this->verification->id,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => 'طلب تسجيل ورشة جديد',

            'message' => 'تم إرسال طلب تسجيل جديد من '
                . $this->verification->first_name . ' '
                . $this->verification->last_name,

            'verification_id' => $this->verification->id,
        ]);
    }
}