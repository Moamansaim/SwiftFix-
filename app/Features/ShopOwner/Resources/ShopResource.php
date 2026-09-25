<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Shop
 */
class ShopResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_name' => $this->shop_name,
            'cover_image' => $this->cover_image,
            'commercial_record_image' => $this->commercial_record_image,
            'country_name' => $this->country->name,
            'city_name' => $this->city->name,
            'district' => $this->district,
            'street' => $this->street,
            'status' => $this->status,

            'services' => $this->services->map(function ($service) {
                return [
                    'service_name' => $service->service_name,
                ];
            }),

            'average_rating' => round(
                $this->reviews()->avg('rating') ?? 0,
                1
            ),

            'is_verified' => $this->is_verified,
        ];
    }
}