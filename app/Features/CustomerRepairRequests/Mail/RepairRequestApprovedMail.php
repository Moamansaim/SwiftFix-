<?php

namespace App\Features\CustomerRepairRequests\Mail;

use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RepairRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomerRepairRequest $repairRequest
    ) {}

    public function build(): self
    {
        return $this
            ->subject('تمت الموافقة على طلب الصيانة في SwiftFix')
            ->view('mail.customer-repair-request.approved');
    }
}