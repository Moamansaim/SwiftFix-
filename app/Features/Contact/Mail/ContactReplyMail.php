<?php

namespace App\Features\Contact\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $reply
    ) {
    }

    public function build(): static
    {
        return $this
            ->subject('الرد على رسالتك - SwiftFix')
            ->view('mail.contact.reply');
    }
}