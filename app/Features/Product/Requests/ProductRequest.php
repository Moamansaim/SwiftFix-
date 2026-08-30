<?php

namespace App\Features\Product\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ProductRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|ValidationRule|Unique>>
     */
    public function rules(): array
    {
        return [
            'product_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'product_name')
                    ->ignore($this->route('id')),
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'device_model_id' => [
                'nullable',
                'integer',
                'exists:device_models,id',
            ],
        ];
    }
}
