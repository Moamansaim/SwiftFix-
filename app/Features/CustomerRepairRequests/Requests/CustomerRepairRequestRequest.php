<?php

namespace App\Features\CustomerRepairRequest\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRepairRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

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

            'phone_number' => [
                'required',
                'string',
                'max:30',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],
        ];
    }
  
   
}