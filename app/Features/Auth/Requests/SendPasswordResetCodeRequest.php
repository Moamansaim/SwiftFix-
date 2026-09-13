<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class responsible for validating the user's email
 * before sending a password reset code.
 *
 * This FormRequest ensures that the provided email is valid
 * and belongs to an existing user.
 */
class SendPasswordResetCodeRequest extends FormRequest
{
    /**
     * Get the validation rules for the password reset code request.
     *
     * The request validates that:
     * - The email field is provided.
     * - The email has a valid format.
     * - The email exists in the users table.
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
             * The value must have a valid email format.
             *
             * exists:users,email:
             * The email must already exist in the users table.
             *
             * This prevents sending a password reset code
             * to an email that is not registered in the system.
             */
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
        ];
    }
}