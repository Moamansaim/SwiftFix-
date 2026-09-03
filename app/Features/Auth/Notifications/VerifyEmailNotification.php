<?php

namespace App\Features\Auth\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Custom email verification notification.
 *
 * Extends Laravel's default VerifyEmail notification
 * to customize the subject and content of the verification email.
 */
class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Build the email verification message.
     *
     * Generates the verification URL and creates a customized
     * email message containing a verification button.
     *
     * @param mixed $notifiable The user receiving the notification.
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        // Generate the email verification URL for the user.
        $verificationUrl = $this->verificationUrl($notifiable);

        // Build and return the customized verification email.
        return (new MailMessage)
            ->subject('تفعيل البريد الإلكتروني')
            ->line('مرحباً، لتفعيل بريدك الإلكتروني اضغط على الزر التالي.')
            ->action('تفعيل البريد الإلكتروني', $verificationUrl)
            ->line('إذا لم تطلب هذا الإجراء، يمكنك تجاهل هذه الرسالة.');
    }
}