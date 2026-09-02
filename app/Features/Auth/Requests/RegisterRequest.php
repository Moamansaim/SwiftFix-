<?php

namespace App\Features\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Request class responsible for validating user registration data.
 *
 * This FormRequest validates all data submitted during account creation
 * before passing the validated data to the registration/use-case layer.
 */
class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules for the registration request.
     *
     * The request validates:
     * - First and last name.
     * - Email format and uniqueness.
     * - International phone number and uniqueness.
     * - Password strength and confirmation.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            /*
             * User first name.
             *
             * required:
             * The first name must be provided.
             *
             * string:
             * The value must be a string.
             *
             * max:30:
             * The first name cannot exceed 30 characters.
             *
             * min:2:
             * The first name must contain at least 2 characters.
             */
            'first_name' => [
                'required',
                'string',
                'max:30',
                'min:2',
            ],

            /*
             * User last name.
             *
             * required:
             * The last name must be provided.
             *
             * string:
             * The value must be a string.
             *
             * max:40:
             * The last name cannot exceed 40 characters.
             *
             * min:2:
             * The last name must contain at least 2 characters.
             */
            'last_name' => [
                'required',
                'string',
                'max:40',
                'min:2',
            ],

            /*
             * User email address.
             *
             * required:
             * The email must be provided.
             *
             * string:
             * The email value must be a string.
             *
             * email:
             * The value must have a valid email format.
             *
             * regex:/^[\x00-\x7F]+$/:
             * Allows only ASCII characters in the email address.
             * This prevents non-ASCII characters from being used.
             *
             * unique:users,email:
             * The email must not already exist in the users table.
             */
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[\x00-\x7F]+$/',
                'unique:users,email',
            ],

            /*
             * User phone number.
             *
             * required:
             * The phone number must be provided.
             *
             * string:
             * The phone number must be provided as a string.
             *
             * phone:INTERNATIONAL:
             * Validates the phone number using the international
             * phone number format provided by the Laravel Phone package.
             *
             * unique:users,phone_number:
             * The phone number must not already exist in the users table.
             */
            'phone_number' => [
                'string',
                'required',
                'phone:INTERNATIONAL',
                'unique:users,phone_number',
            ],

            /*
             * User password.
             *
             * required:
             * The password must be provided.
             *
             * string:
             * The password must be a string.
             *
             * Password::min(8):
             * The password must contain at least 8 characters.
             *
             * ->max(20):
             * The password cannot exceed 20 characters.
             *
             * ->numbers():
             * Requires at least one numeric character.
             *
             * ->letters():
             * Requires at least one alphabetic character.
             *
             * ->mixedCase():
             * Requires both uppercase and lowercase letters.
             *
             * ->symbols():
             * Requires at least one special character.
             *
             * confirmed:
             * Requires a matching password_confirmation field.
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