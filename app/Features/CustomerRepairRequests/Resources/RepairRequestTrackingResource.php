<?php

namespace App\Features\CustomerRepairRequests\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RepairRequestTrackingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'shop_name' => $this->shop?->shop_name,
            'status' => $this->status,
            'device_model_name' => $this->deviceModel?->name,
            'service_name' => $this->service?->name,
            'created_at' => $this->created_at,
        ];
    }
}