<?php

namespace App\Features\Auth\Requests;

use App\Features\Auth\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class responsible for authorizing and completing
 * the user's email verification process.
 *
 * This request validates the verification link parameters
 * before allowing the user's email to be marked as verified.
 */
class VerifyEmailRequest extends FormRequest
{
    /**
     * Determine whether the current request is authorized
     * to verify the user's email address.
     *
     * The verification link contains:
     * - The user's ID.
     * - A hash generated from the user's email.
     *
     * Both values are checked to ensure that the verification
     * link belongs to the requested user and has not been
     * tampered with.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        /*
         * Retrieve the user using the ID provided
         * in the verification route.
         */
        $user = User::find($this->route('id'));

        /*
         * If the user does not exist, the request
         * is not authorized.
         */
        if (! $user) {
            return false;
        }

        /*
         * Compare the user's actual primary key with the
         * ID received from the route.
         *
         * hash_equals() performs a timing-safe comparison,
         * which helps prevent timing attacks.
         */
        if (! hash_equals(
            (string) $user->getKey(),
            (string) $this->route('id')
        )) {
            return false;
        }

        /*
         * Generate the SHA-1 hash of the email address
         * used for verification and compare it with the
         * hash received in the verification URL.
         *
         * This ensures that the verification URL corresponds
         * to the user's current email address.
         */
        if (! hash_equals(
            sha1($user->getEmailForVerification()),
            (string) $this->route('hash')
        )) {
            return false;
        }

        /*
         * All verification checks passed successfully.
         */
        return true;
    }

    /**
     * Get the validation rules for the email verification request.
     *
     * No traditional request-body validation is required because
     * the verification data is received through route parameters.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Complete the email verification process.
     *
     * This method:
     * - Retrieves the user from the route ID.
     * - Checks whether the email has already been verified.
     * - Marks the email as verified.
     * - Dispatches Laravel's Verified event.
     *
     * @return bool
     *         Returns false if the email was already verified,
     *         otherwise returns true after successful verification.
     */
    public function fulfill(): bool
    {
        /*
         * Retrieve the user using the ID from the verification URL.
         *
         * findOrFail() automatically throws a 404 exception
         * if the user does not exist.
         */
        $user = User::findOrFail($this->route('id'));

        /*
         * Prevent verifying an email that has already
         * been verified.
         */
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        /*
         * Mark the user's email address as verified.
         *
         * This method is defined in the User model / MustVerifyEmail
         * implementation.
         */
        $user->markEmailAsVerified();

        /*
         * Dispatch Laravel's Verified event.
         *
         * Other parts of the application can listen to this event
         * and perform additional actions after email verification.
         */
        event(new Verified($user));

        /*
         * Email verification completed successfully.
         */
        return true;
    }
}