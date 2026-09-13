<?php

namespace App\Features\Review\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewReplyRequest extends FormRequest
{
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
