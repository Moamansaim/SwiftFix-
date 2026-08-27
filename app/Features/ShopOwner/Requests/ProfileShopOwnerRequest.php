<?php

namespace App\Features\ShopOwner\Requests;


use App\Features\ShopOwner\Rules\TimeAfter;
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
                'unique:shops,shop_name'
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
                'min:1',
            ],

            'working_hours.*.day' => [
                'required',
                'string',
                'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            ],

            'working_hours.*.from' => [
                'required',
                'date_format:H:i',
            ],

            'working_hours.*.to' => [
                'required',
                'date_format:H:i',
                new TimeAfter(),
            ],

            'service_ids' => [
                'required',
                'array',
                'min:1',
            ],

            'service_ids.*' => [
                'required',
                'integer',
                'exists:services,id',
            ],
        ];
    }
}