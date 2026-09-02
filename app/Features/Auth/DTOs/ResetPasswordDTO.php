<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for resetting the user's password.
 *
 * Contains the user's email, password reset verification code,
 * and the new password.
 */
class ResetPasswordDTO
{
    /**
     * Create a new ResetPasswordDTO instance.
     *
     * @param string $email User's email address.
     * @param string $code Password reset verification code.
     * @param string $password The new password.
     */
    public function __construct(
        public string $email,
        public string $code,
        public string $password
    ) {}
}