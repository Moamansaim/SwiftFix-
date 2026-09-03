<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class responsible for validating user login data.
 *
 * This FormRequest validates the credentials submitted by the user
 * before passing them to the authentication/use-case layer.
 */
class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules for the login request.
     *
     * Validation includes:
     * - A required and valid email that exists in the users table.
     * - A required password.
     * - An optional remember_me flag that must be boolean.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /*
             * User email.
             *
             * required:
             * The email field must be provided.
             *
             * email:
             * The value must have a valid email format.
             *
             * exists:users,email:
             * The email must already exist in the users table.
             */
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],

            /*
             * User password.
             *
             * required:
             * The password must be provided.
             *
             * Note:
             * The actual password correctness is checked during
             * the authentication process, not by this validation rule.
             */
            'password' => [
                'required',
            ],

            /*
             * Remember login option.
             *
             * nullable:
             * The field is optional and can be omitted.
             *
             * boolean:
             * If provided, the value must be boolean
             * (true/false, 1/0, etc. according to Laravel validation).
             */
            'remember_me' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}