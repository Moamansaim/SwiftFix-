<?php

namespace App\Features\ShopOwner\Requests;

use App\Features\ShopOwner\Rules\TimeAfter;
use Illuminate\Foundation\Http\FormRequest;

class ProfileShopOwnerRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
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

            'commercial_record_image' => [
                'nullable',
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

            'district' => [
                'required',
                'string',
                'min:2',
                'max:255',
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
                new TimeAfter,
            ],

            'services' => [
                'required',
                'array',
                'min:1',
            ],

            'services.*' => [
                'required',
                'array',
            ],

            'services.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ];
    }
}