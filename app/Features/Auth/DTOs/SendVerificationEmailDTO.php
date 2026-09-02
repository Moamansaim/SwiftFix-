<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for sending an email verification message.
 *
 * Contains the user's email address that will receive
 * the email verification message.
 */
class SendVerificationEmailDTO
{
    /**
     * Create a new SendVerificationEmailDTO instance.
     *
     * @param string $email User's email address.
     */
    public function __construct(
        public string $email,
    ) {}
}