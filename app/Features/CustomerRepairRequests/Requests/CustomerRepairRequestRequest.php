<?php

namespace App\Features\CustomerRepairRequests\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRepairRequestRequest extends FormRequest
{
 
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'shop_id' => [
                'required',
                'integer',
                'exists:shops,id',
            ],

            'device_model_id' => [
                'required',
                'integer',
                'exists:device_models,id',
            ],

            'service_id' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'description' => [
                'required',
                'string',
                'max:2000',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],
        ];
    }
}