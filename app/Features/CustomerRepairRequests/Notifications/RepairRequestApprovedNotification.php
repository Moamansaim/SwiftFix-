<?php

namespace App\Features\CustomerRepairRequests\Notifications;

use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RepairRequestApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public CustomerRepairRequest $repairRequest
    ) {}

    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'تمت الموافقة على طلب الصيانة',
            'message' => 'تمت الموافقة على طلب الصيانة الخاص بك.',
            'repair_request_id' => $this->repairRequest->id,
        ];
    }
}
