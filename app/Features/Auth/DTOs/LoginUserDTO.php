<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for user login.
 *
 * Contains the user's login credentials and the optional
 * "remember me" option.
 */
class LoginUserDTO
{
    /**
     * Create a new LoginUserDTO instance.
     *
     * @param string $email User's email address.
     * @param string $password User's password.
     * @param bool|null $remember_me Whether to remember the user's login.
     */
    public function __construct(
        public string $email,
        public string $password,
        public ?bool $remember_me = false
    ) {}
}