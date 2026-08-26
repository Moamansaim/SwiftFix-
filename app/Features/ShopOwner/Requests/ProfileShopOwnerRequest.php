<?php

namespace App\Features\ShopOwner\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileShopOwnerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shop_name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],

            'description' => [
                'required',
                'string',
                'max:1000',
            ],

            'cover_image' => [
                'required',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],

            'country_id' => [
                'required',
                'integer',
                'exists:countries,id',
            ],

            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],

            'district_id' => [
                'required',
                'integer',
                'exists:districts,id',
            ],

            'street' => [
                'required',
                'string',
                'min:2',
                'max:255',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],

            'working_hours' => [
                'required',
                'array',
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