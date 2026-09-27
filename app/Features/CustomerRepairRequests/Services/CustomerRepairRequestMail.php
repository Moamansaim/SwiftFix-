<?php

namespace App\Features\CustomerRepairRequests\Services;

use App\Features\CustomerRepairRequests\Mail\RepairRequestApprovedMail;
use App\Features\CustomerRepairRequests\Mail\RepairRequestRejectedMail;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Support\Facades\Mail;

class CustomerRepairRequestMail
{
    /**
     * Send an approval email to the customer.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The approved repair request.
     *
     * @return void
     *
     * @hint Sends an email notification to the customer
     *        after the repair request has been approved.
     */
    public function sendApproval(
        CustomerRepairRequest $repairRequest
    ): void {
        Mail::to($repairRequest->user->email)->send(
            new RepairRequestApprovedMail(
                $repairRequest
            )
        );
    }

    /**
     * Send a rejection email to the customer.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The rejected repair request.
     *
     * @return void
     *
     * @hint Sends an email notification to the customer
     *        after the repair request has been rejected.
     */
    public function sendRejection(
        CustomerRepairRequest $repairRequest
    ): void {
        Mail::to($repairRequest->user->email)->send(
            new RepairRequestRejectedMail(
                $repairRequest
            )
        );
    }
}