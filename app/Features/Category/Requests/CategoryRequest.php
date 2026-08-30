<?php

namespace App\Features\Category\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class CategoryRequest extends FormRequest
{
    /**
     * @return array<string, array<int, string|ValidationRule|Unique>>
     */
    public function rules(): array
    {
        return [
            'category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'category_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}
