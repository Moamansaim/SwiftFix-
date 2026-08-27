<?php

namespace App\Features\Brand\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class BrandRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|Unique>>
     */
    public function rules(): array
    {
        return [
            'brand_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'brand_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}
