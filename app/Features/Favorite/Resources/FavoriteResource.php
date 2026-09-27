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
     * Transform the resource into an array.
     *
     * @param Request $request
     *        The current HTTP request.
     *
     * @return array<string, mixed>
     *
     * @hint Returns the favorite shop information including
     *        its location details and available services.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'shop_name' => $this->shop?->shop_name,

            'cover_image' => $this->shop?->cover_image,

            'country_name' => $this->shop?->country?->name,

            'city_name' => $this->shop?->city?->name,

            'district' => $this->shop?->district,

            'street' => $this->shop?->street,

            'status' => $this->shop?->status,

            'services' => $this->shop?->services->map(function ($service) {
                return [
                    'service_name' => $service->service_name,
                ];
            }),
        ];
    }
}