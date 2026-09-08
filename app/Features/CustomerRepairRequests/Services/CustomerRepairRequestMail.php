<?php

namespace App\Features\CustomerRepairRequests\Services;

use App\Features\CustomerRepairRequests\Mail\RepairRequestApprovedMail;
use App\Features\CustomerRepairRequests\Mail\RepairRequestRejectedMail;
use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Support\Facades\Mail;

class CustomerRepairRequestMail
{
    public function sendApproval(
        CustomerRepairRequest $repairRequest
    ): void {

        Mail::to($repairRequest->user->email)->send(
            new RepairRequestApprovedMail(
                $repairRequest
            )
        );
    }

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