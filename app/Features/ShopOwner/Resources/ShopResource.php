<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** * @mixin Shop */
class ShopResource extends JsonResource
{
    /** * @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shop_name' => $this->shop_name,
            'cover_image' => $this->cover_image,
            'country' => $this->country->country_name,
            'city' => $this->city->city_name,
            'district' => $this->district,
            'street' => $this->street,
            'status' => $this->status,
            'services' => $this->services->map(function ($service) {
                return [
                    'service_name' => $service->service_name,
                ];
            }),
        ];
    }
}