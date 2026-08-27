<?php

namespace App\Features\ShopOwner\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopOwnerVerificationRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:30',
                'min:2',
            ],

            'last_name' => [
                'required',
                'string',
                'max:40',
                'min:2',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\x00-\x7F]+$/',
                'unique:shop_owner_verifications,email',
            ],

            'phone_number' => [
                'string',
                'required',
                'phone:INTERNATIONAL',
                'unique:shop_owner_verifications,phone_number',
            ],

            'national_id_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048',
            ],

            'country_id' => [
                'required',
                'exists:countries,id',
            ],

            'service_ids' => [
                'required',
                'array',
            ],

            'service_ids.*' => [
                'required',
                'integer',
                'exists:services,id',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}
