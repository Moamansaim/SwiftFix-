<?php

namespace App\Features\Contact\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactReplyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reply' => [
                'required',
                'string',
                'max:10000',
            ],
        ];
    }
}