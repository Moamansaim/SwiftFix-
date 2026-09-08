<?php

namespace App\Features\Review\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    /**
     * Get the validation rules for the review request.
     *
     * @return array<string, array<int, string>>
     *
     * @hint Validates the rating to ensure it is required, an integer,
     *        and has a value between 1 and 5. The comment is optional,
     *        must be a string when provided, and cannot exceed 1000
     *        characters.
     */
    public function rules(): array
    {
        return [
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
}