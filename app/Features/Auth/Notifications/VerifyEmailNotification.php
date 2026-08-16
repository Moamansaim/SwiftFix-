<?php

namespace App\Features\Auth\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('تفعيل البريد الإلكتروني')
            ->line('مرحباً، لتفعيل بريدك الإلكتروني اضغط على الزر التالي.')
            ->action('تفعيل البريد الإلكتروني', $verificationUrl)
            ->line('إذا لم تطلب هذا الإجراء، يمكنك تجاهل هذه الرسالة.');
    }
}
