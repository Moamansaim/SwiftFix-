<?php

namespace App\Features\Auth\DTOs;

class PasswordVerifyEmailUserDTO
{
    public function __construct(
        public string $email,
    ) {}
}