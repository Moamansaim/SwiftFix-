<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],

            'code' => [
                'required',
            ],

            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->max(20)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
                'confirmed',
            ],
        ];
    }
}
