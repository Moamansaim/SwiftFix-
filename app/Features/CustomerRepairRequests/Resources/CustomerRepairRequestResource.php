<?php

namespace App\Features\CustomerRepairRequests\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerRepairRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *        The current HTTP request.
     *
     * @return array
     *
     * @hint Returns the repair request with its related
     *        customer, shop, device model, and service data.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'customer' => [
                'id' => $this->user?->id,
                'first_name' => $this->user?->first_name,
                'last_name' => $this->user?->last_name,
                'email' => $this->user?->email,
            ],

            'shop' => [
                'id' => $this->shop?->id,
                'shop_name' => $this->shop?->shop_name,
            ],

            'device_model' => [
                'id' => $this->deviceModel?->id,
                'name' => $this->deviceModel?->device_model_name,
            ],

            'service' => [
                'id' => $this->service?->id,
                'name' => $this->service?->service_name,
            ],

            'description' => $this->description,

            'image' => $this->image,

            'status' => $this->status,

            'phone_number' => $this->phone_number,

            'address' => $this->address,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at
        ];
    }
}