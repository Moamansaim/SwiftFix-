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
                'min:2',
            ],

            'last_name' => [
                'required',
                'string',
                'max:40',
                'min:2',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\x00-\x7F]+$/',
                'unique:users,email',
            ],

            'phone_number' => [
                'string',
                'required',
                'phone:INTERNATIONAL',
                'unique:users,phone_number',
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
