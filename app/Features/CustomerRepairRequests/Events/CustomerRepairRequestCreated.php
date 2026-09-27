<?php

namespace App\Features\CustomerRepairRequests\Events;

use App\Features\CustomerRepairRequests\Models\CustomerRepairRequest;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerRepairRequestCreated implements ShouldBroadcast
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public CustomerRepairRequest $repairRequest
    ) {}

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        $channel = 'shop.' . $this->repairRequest->shop->user_id;

        Log::info('CustomerRepairRequestCreated broadcasting', [
            'channel' => $channel,
            'shop_id' => $this->repairRequest->shop_id,
            'user_id' => $this->repairRequest->shop->user_id,
        ]);

        return [
            new PrivateChannel($channel),
        ];
    }

    /**
     * The event name received by the frontend.
     */
    public function broadcastAs(): string
    {
        return 'repair-request.created';
    }

    /**
     * The data sent to the frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'repair_request_id' => $this->repairRequest->id,
            'shop_id' => $this->repairRequest->shop_id,
            'customer_id' => $this->repairRequest->user_id,
            'service_id' => $this->repairRequest->service_id,
            'message' => 'لديك طلب صيانة جديد.',
        ];
    }
}