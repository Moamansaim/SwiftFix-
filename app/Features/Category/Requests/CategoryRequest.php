<?php

namespace App\Features\Category\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

/**
 * Category Request
 *
 * Handles validation rules for creating and updating product categories.
 *
 * The same request class can be used for both store and update operations.
 */
class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The category name is required, must be a string, and cannot
     * exceed 255 characters.
     *
     * The name must also be unique in the `categories` table.
     * During an update operation, the current category is ignored
     * to allow the category to keep its existing name.
     *
     * @return array<string, array<int, string|ValidationRule|Unique>>
     */
    public function rules(): array
    {
        return [
            'category_name' => [
                // The category name must be provided.
                'required',

                // The category name must be a string.
                'string',

                // The category name cannot exceed 255 characters.
                'max:255',

                // The category name must be unique in the categories table.
                // The current category is ignored when updating.
                Rule::unique('categories', 'category_name')
                    ->ignore($this->route('id')),
            ],
        ];
    }
}