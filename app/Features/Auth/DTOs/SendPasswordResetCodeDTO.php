<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for sending a password reset code.
 *
 * Contains the user's email address that will receive
 * the password reset verification code.
 */
class SendPasswordResetCodeDTO
{
    /**
     * Create a new SendPasswordResetCodeDTO instance.
     *
     * @param string $email User's email address.
     */
    public function __construct(
        public string $email,
    ) {}
}