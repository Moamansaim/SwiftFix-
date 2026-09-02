<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class responsible for validating the user's email
 * before sending a verification email.
 *
 * This FormRequest ensures that the provided email is valid
 * and belongs to an existing user.
 */
class SendVerificationEmailRequest extends FormRequest
{
    /**
     * Get the validation rules for the verification email request.
     *
     * The request validates that:
     * - The email field is provided.
     * - The email has a valid email format.
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
             * This ensures that the verification email is sent
             * only for a registered user.
             */
            'email' => [
                'required',
                'email',
                'exists:users,email',
            ],
        ];
    }
}