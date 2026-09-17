<?php

namespace App\Features\CustomerRepairRequest\Services;

use App\Features\CustomerRepairRequest\Mail\RepairRequestApprovedMail;
use App\Features\CustomerRepairRequest\Mail\RepairRequestRejectedMail;
use App\Features\CustomerRepairRequest\Models\CustomerRepairRequest;
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