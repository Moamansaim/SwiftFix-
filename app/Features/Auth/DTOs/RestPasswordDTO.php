<?php

namespace App\Features\Auth\DTOs;

class RestPasswordDTO
{
    public function __construct(
        public string $email,
        public string $code,
        public string $password
    ) {}
}