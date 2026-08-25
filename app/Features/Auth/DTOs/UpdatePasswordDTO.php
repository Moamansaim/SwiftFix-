<?php

namespace App\Features\Auth\DTOs;

class UpdatePasswordDTO
{
    public function __construct(
        public string $current_password,
        public string $password,
    ) {}
}