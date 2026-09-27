<?php

namespace App\Features\DeviceModel\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeviceModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *        The current HTTP request.
     *
     * @return array
     *
     * @hint Returns the device model information along with
     *        the associated brand name.
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