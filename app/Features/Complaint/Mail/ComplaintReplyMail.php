<?php

namespace App\Features\Complaint\Mail;

use App\Features\Complaint\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ComplaintReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Complaint $complaint
    ) {
    }

    /**
     * Build the message.
     */
    public function build(): static
    {
        return $this
            ->subject('الرد على شكواك - SwiftFix')
            ->view('mail.complaint.reply');
    }
}