<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for changing the user's password.
 *
 * Contains the current password for verification
 * and the new password that will replace it.
 */
class ChangePasswordDTO
{
    /**
     * Create a new ChangePasswordDTO instance.
     *
     * @param string $current_password The user's current password.
     * @param string $password The new password.
     */
    public function __construct(
        public string $current_password,
        public string $password,
    ) {}
}