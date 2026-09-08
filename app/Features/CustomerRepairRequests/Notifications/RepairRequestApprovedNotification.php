<?php

namespace App\Features\CustomerRepairRequests\Notifications;

use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Database notification sent to the customer when a repair request
 * is approved by the shop.
 */
class RepairRequestApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new repair request approved notification.
     *
     * @param CustomerRepairRequest $repairRequest
     *        The repair request that was approved.
     *
     * @hint The repair request is used to include its ID
     *        in the database notification.
     */
    public function __construct(
        public CustomerRepairRequest $repairRequest
    ) {}

    /**
     * Get the notification delivery channels.
     *
     * @param object $notifiable
     *        The user who will receive the notification.
     *
     * @return array
     *
     * @hint Stores the notification in the database.
     */
    public function via(object $notifiable): array
    {
        return [
            'database',
        ];
    }

    /**
     * Get the data stored in the database notification.
     *
     * @param object $notifiable
     *        The user who will receive the notification.
     *
     * @return array
     *
     * @hint Contains the notification title, message,
     *        and related repair request ID.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'تمت الموافقة على طلب الصيانة',
            'message' => 'تمت الموافقة على طلب الصيانة الخاص بك.',
            'repair_request_id' => $this->repairRequest->id,
        ];
    }
}