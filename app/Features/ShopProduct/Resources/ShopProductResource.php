<?php

namespace App\Features\ShopProduct\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Features\ShopProduct\Models\ShopProduct
 */
class ShopProductResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'product_name' => $this->product?->product_name,

            'device_model_name' => $this->product?->deviceModel?->model_name,

            'category_name' => $this->product?->category?->category_name,

            'quantity' => $this->quantity,

            'price' => $this->price,

            'image' => $this->image,

            'description' => $this->description,

            'status' => $this->status,

            'created_at' => $this->created_at
        ];
    }
}