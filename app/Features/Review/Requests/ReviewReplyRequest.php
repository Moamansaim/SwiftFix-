<?php

namespace App\Features\Review\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewReplyRequest extends FormRequest
{
    /**
     * Get the validation rules for the shop owner's review reply.
     *
     * @return array<string, array<int, string>>
     *
     * @hint Validates the reply to ensure it is required, contains
     *        a string value, and does not exceed 1000 characters.
     */
    public function rules(): array
    {
        return [
            'reply' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }
}