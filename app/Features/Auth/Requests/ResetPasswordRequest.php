<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request class responsible for validating password reset data.
 *
 * This FormRequest validates the user's email, reset code,
 * and new password before passing the data to the password
 * reset/use-case layer.
 */
class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules for the password reset request.
     *
     * The request validates:
     * - The email exists in the users table.
     * - A reset code is provided.
     * - The new password meets the required security rules.
     * - The password confirmation matches the new password.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /*
             * User email address.
             *
             * required:
             * The email must be provided.
             *
             * email:
             * The email must have a valid email format.
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
             * Password reset verification code.
             *
             * required:
             * The reset code must be provided.
             *
             * Note:
             * This rule only checks that the code was provided.
             * The actual code verification, expiration check,
             * and comparison with the stored hashed code should
             * be handled in the UseCase/Repository layer.
             */
            'code' => [
                'required',
            ],

            /*
             * New password.
             *
             * required:
             * The new password must be provided.
             *
             * string:
             * The password must be a string.
             *
             * Password::min(8):
             * Requires at least 8 characters.
             *
             * ->max(20):
             * The password cannot exceed 20 characters.
             *
             * ->numbers():
             * Requires at least one number.
             *
             * ->letters():
             * Requires at least one letter.
             *
             * ->mixedCase():
             * Requires both uppercase and lowercase letters.
             *
             * ->symbols():
             * Requires at least one special character.
             *
             * confirmed:
             * Requires a password_confirmation field
             * that matches the new password.
             */
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