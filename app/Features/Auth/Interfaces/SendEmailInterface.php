<?php

namespace App\Features\Auth\Interfaces;

use App\Features\Auth\Models\User;

/**
 * Interface for sending authentication-related emails.
 *
 * Defines the contract for email operations used
 * during the authentication process.
 */
interface SendEmailInterface
{
    /**
     * Send a password reset code to the user's email.
     *
     * Uses the provided user information to generate and
     * send a password reset code to their registered email address.
     *
     * @param User $user The user who requested the password reset.
     * @return void
     */
    public function sendResetCodeToEmail(User $user): void;
}