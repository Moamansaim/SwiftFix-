<?php

namespace App\Features\Contact\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ContactMessageRequest
 *
 * Handles validation for contact messages submitted
 * through the application's contact form.
 *
 * This request ensures that all required fields are present
 * and that the submitted values match the expected data types
 * and length constraints before the request reaches the controller.
 */
class ContactMessageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * The validation rules ensure that:
     *
     * - full_name is required, must be a string, and cannot exceed 255 characters.
     * - email is required, must be a valid email address,
     *   and cannot exceed 255 characters.
     * - subject is required, must be a string, and cannot exceed 255 characters.
     * - message is required, must be a string, and cannot exceed 5000 characters.
     *
     * If any validation rule fails, Laravel automatically returns
     * a validation error response and the controller method is not executed.
     *
     * @return array<string, array<int, string>>
     *         An array containing the validation rules for each
     *         contact message field.
     */
    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'message' => [
                'required',
                'string',
                'max:5000',
            ],
        ];
    }
}