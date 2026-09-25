<?php

namespace App\Features\Complaint\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyComplaintRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules.
     */
    public function rules(): array
    {
        return [
            'admin_reply' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }
}