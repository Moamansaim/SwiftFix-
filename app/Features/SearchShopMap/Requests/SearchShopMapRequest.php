<?php

namespace App\Features\SearchShopMap\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchShopMapRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Customer Location
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id',
            ],

            'product_id' => [
                'nullable',
                'integer',
                'exists:products,id',
            ],
        ];
    }

    /**
     * Validate that exactly one search type is provided.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {

            /*
            |--------------------------------------------------------------------------
            | Service or Product is required
            |--------------------------------------------------------------------------
            */

            if (
                !$this->filled('service_id') &&
                !$this->filled('product_id')
            ) {
                $validator->errors()->add(
                    'search',
                    'يجب تحديد خدمة أو قطعة غيار للبحث.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Cannot search by both
            |--------------------------------------------------------------------------
            */

            if (
                $this->filled('service_id') &&
                $this->filled('product_id')
            ) {
                $validator->errors()->add(
                    'search',
                    'يمكن البحث عن خدمة أو قطعة غيار وليس الاثنين معًا.'
                );
            }
        });
    }
}