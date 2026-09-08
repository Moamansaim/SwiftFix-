<?php

namespace App\Features\SearchShopMap\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchShopMapRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
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
            'latitude' => [
                'nullable',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'nullable',
                'numeric',
                'between:-180,180',
            ],

            'device_model_id' => [
                'nullable',
                'integer',
                'exists:device_models,id',
            ],

            'service_id' => [
                'nullable',
                'integer',
                'exists:services,id',
            ],

            'spare_part_id' => [
                'nullable',
                'integer',
                'exists:products,id',
            ],

            'min_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'max_price' => [
                'nullable',
                'numeric',
                'gte:min_price',
            ],

            'radius' => [
                'nullable',
                'numeric',
                'min:1',
                'max:100',
            ],
        ];
    }

    /**
     * Validate the request after the basic rules.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (
                !$this->filled('service_id') &&
                !$this->filled('spare_part_id')
            ) {
                $validator->errors()->add(
                    'search',
                    'يجب تحديد خدمة أو قطعة غيار للبحث.'
                );
            }

            if (
                $this->filled('service_id') &&
                $this->filled('spare_part_id')
            ) {
                $validator->errors()->add(
                    'search',
                    'يمكن البحث عن خدمة أو قطعة غيار وليس الاثنين معًا.'
                );
            }
        });
    }
}