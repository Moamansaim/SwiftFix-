<?php

namespace App\Features\ShopProduct\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'device_model_id' => [
                'nullable',
                'integer',
                'exists:device_models,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'available',
                    'out_of_stock',
                ]),
            ],
        ];
    }
}
