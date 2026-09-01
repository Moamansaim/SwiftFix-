<?php

namespace App\Features\Favorite\Resources;

use App\Features\Favorite\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Favorite
 */
class FavoriteResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'shop_name' => $this->shop->shop_name,

            'description' => $this->shop->description,

            'cover_image' => $this->shop->cover_image,

            'country' => $this->shop->country?->country_name,

            'city' => $this->shop->city?->city_name,

            'district' => $this->shop->district,

            'street' => $this->shop->street,

            'working_hours' => $this->shop->working_hours,

            'services' => $this->shop->services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service_name' => $service->service_name,
                ];
            }),
        ];
    }
}
