<?php

namespace App\Features\Auth\DTOs;

class RegisterUserDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $email,
        public string $phone_number,
        public string $password
    ) {}
}
