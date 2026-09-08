<?php

namespace App\Features\Product\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules for the product request.
     *
     * @return array<string, array<int, string|ValidationRule|Unique>>
     *
     * @hint Validates the product name to ensure it is required, a string,
     *        and does not exceed 255 characters. It also ensures that the
     *        product name is unique, while allowing the current product
     *        to keep its existing name during an update.
     *
     *        Validates the category ID to ensure it is required, an integer,
     *        and references an existing category in the categories table.
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
        ];
    }
}