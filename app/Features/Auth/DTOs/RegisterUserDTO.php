<?php

namespace App\Features\Auth\DTOs;

/**
 * Data Transfer Object for user registration.
 *
 * Contains the data required to create a new user account.
 */
class RegisterUserDTO
{
    /**
     * Create a new RegisterUserDTO instance.
     *
     * @param string $first_name User's first name.
     * @param string $last_name User's last name.
     * @param string $email User's email address.
     * @param string $phone_number User's phone number.
     * @param string $password User's password.
     */
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $phone_number,
        public string $password
    ) {}
}