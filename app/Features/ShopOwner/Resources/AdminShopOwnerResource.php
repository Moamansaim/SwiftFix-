<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminShopOwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'shop_name' => $this->shop?->shop_name,
            'commercial_record_image' => $this->shop?->commercial_record_image,
            'country' => $this->shop?->country?->name,
            'city' => $this->shop?->city?->name,
            'shop_status' => $this->shop?->status,
            'shop_id' => $this->shop?->id,
            'is_verified' => $this->shop?->is_verified,
        ];
    }
}