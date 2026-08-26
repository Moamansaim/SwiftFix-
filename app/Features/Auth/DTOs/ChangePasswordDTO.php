<?php

namespace App\Features\Auth\DTOs;

class ChangePasswordDTO
{
    public function __construct(
        public string $current_password,
        public string $password,
    ) {}
}