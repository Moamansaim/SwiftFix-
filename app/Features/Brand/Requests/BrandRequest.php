<?php

namespace App\Features\Brand\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

/**
 * Brand Request
 *
 * Handles validation rules for creating and updating brands.
 *
 * The same request can be used for both store and update operations.
 */
class BrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The brand name is required, must be a string, and cannot
     * exceed 255 characters.
     *
     * The name must also be unique in the `brands` table.
     * During an update operation, the current brand is ignored
     * to prevent the validation from rejecting its existing name.
     *
     * @return array<string, array<int, string|Unique>>
     */
    public function rules(): array
    {
        return [
            'brand_name' => [
                // The brand name must be provided.
                'required',

                // The brand name must be a string.
                'string',

                // The brand name cannot exceed 255 characters.
                'max:255',

                // The brand name must be unique in the brands table.
                // The current brand is ignored when updating.
                Rule::unique('brands', 'brand_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}