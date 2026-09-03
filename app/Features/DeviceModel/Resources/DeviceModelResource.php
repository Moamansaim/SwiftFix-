<?php

namespace App\Features\DeviceModel\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'device_model_name' => $this->device_model_name,
            'brand_name' => $this->brand?->brand_name,
            'created_at' => $this->created_at,
        ];
    }
}