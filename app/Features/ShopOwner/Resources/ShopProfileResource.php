<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** * @mixin Shop */
class ShopProfileResource extends JsonResource
{
    /** * @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'shop_name' => $this->shop_name,
            'description' => $this->description,
            'cover_image' => $this->cover_image,
            'country_id' => $this->country_id,
            'city_id' => $this->city_id,
            'district' => $this->district,
            'street' => $this->street,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'working_hours' => $this->working_hours,
            'services' => $this->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service_name' => $service->service_name,
                    'price' => $service->pivot->price,
                ];
            }),
        ];
    }
}
