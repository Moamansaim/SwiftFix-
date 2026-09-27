<?php

namespace App\Features\CustomerRepairRequests\Mail;

use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Email notification sent to the customer when a repair request
 * is rejected by the shop.
 */
class RepairRequestRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new repair request rejected mail.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The rejected repair request.
     *
     * @hint The repair request is passed to the email view
     *        to display its relevant information.
     */
    public function __construct(
        public CustomerRepairRequest $repairRequest
    ) {}

    /**
     * Build the repair request rejection email.
     *
     * @return self
     *
     * @hint Uses the customer repair request rejection email view.
     */
    public function build(): self
    {
        return $this
            ->subject('تم رفض طلب الصيانة في SwiftFix')
            ->view('mail.customer-repair-request.rejected');
    }
}