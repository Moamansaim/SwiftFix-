<?php

namespace App\Features\ShopOwner\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** * @mixin Shop */
class ShopDetailsResource extends JsonResource
{
    /** * @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'shop_name' => $this->shop_name,

            'description' => $this->description,

            'cover_image' => $this->cover_image,

            'country_name' => $this->country?->name,

            'city_name' => $this->city?->name,

            'district' => $this->district,

            'street' => $this->street,

            'latitude' => $this->latitude,

            'longitude' => $this->longitude,

            'working_hours' => $this->working_hours,

            'phone_number' => $this->user->phone_number,

            'email' => $this->user->email,

            'status' => $this->status,

            'services' => $this->services->map(function ($service) {
                return [
                    'id' => $service->id,

                    'service_name' => $service->service_name,

                    'price' => $service->pivot->price,
                ];
            }),

            'featuresShop' => $this->featuresShop->map(function ($featuresShop) {
                return [
                    'feature_name' => $featuresShop->feature,
                ];
            }),


            'products' => $this->shopProducts->map(function ($shopProduct) {
                return [
                    'product_name' => $shopProduct->product->product_name,
                    'price' => $shopProduct->price,
                    'quantity' => $shopProduct->quantity,
                    'image' => $shopProduct->image,
                    'description' => $shopProduct->description,
                    'status' => $shopProduct->status,
                ];
            }),
        ];
    }
}