<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;


class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
            'password' => [
                'required',
                
            ],
            'remember_me' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}