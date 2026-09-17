<?php

namespace App\Features\ShopOwner\Events;

use App\Features\ShopOwner\Models\ShopOwnerVerification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewShopOwnerVerificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ShopOwnerVerification $verification
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-shop-owner-verification';
    }

    public function broadcastWith(): array
    {
        return [
            'verification_id' => $this->verification->id,
        ];
    }
}