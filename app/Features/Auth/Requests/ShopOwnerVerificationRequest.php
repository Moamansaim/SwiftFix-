<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;


class ShopOwnerVerificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:30',
                'min:2'
            ],
            'last_name' => [
                'required',
                'string',
                'max:40',
                'min:2'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\x00-\x7F]+$/',
                'unique:users,email',
            ],
            'phone_number' => [
                'string',
                'required',
                'phone:INTERNATIONAL',
                'unique:users,phone_number',
            ],
            'national_id_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
            'county_id' => [
                'required',
                'exists:countries,id'
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

        ];
    }
}