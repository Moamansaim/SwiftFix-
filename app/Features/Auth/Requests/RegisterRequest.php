<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:30',
                'min:2'
            ],
            'last_name' => [
                'required',
                'string',
                'max:40',
                'min:2'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'unique:users,email',
            ],
            'phone_number' => [
                'string',
                'unique:users,phone_number',
            ],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],
        ];
    }
}