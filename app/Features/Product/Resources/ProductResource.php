<?php

namespace App\Features\Product\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *        The current HTTP request instance.
     *
     * @return array
     *
     * @hint Returns the product data including its ID, name,
     *        category name, and creation date. The category name
     *        is retrieved from the product's related category.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'category_name' => $this->category->category_name,
            'created_at' => $this->created_at,
        ];
    }
}