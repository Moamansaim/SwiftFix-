<?php

namespace App\Features\FeatureShop\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureShopRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'feature' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
            ],

        ];
    }
}
