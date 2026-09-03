<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Form request for changing the authenticated user's password.
 *
 * Handles validation of the current password and the new password
 * before passing the data to the password change process.
 */
class ChangePasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * Validates the current password and ensures that the new password
     * meets the required security requirements and matches its confirmation.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Validate that the current password is required and correct.
            'current_password' => [
                'required',
                'current_password',
            ],

            // Validate the new password and its confirmation.
            'password' => [
                'required',
                'confirmed',

                // Require a password between 8 and 20 characters
                // containing letters, numbers, mixed case, and symbols.
                Password::min(8)
                    ->max(20)
                    ->numbers()
                    ->letters()
                    ->mixedCase()
                    ->symbols(),
            ],
        ];
    }
}