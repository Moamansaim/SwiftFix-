<?php

namespace App\Features\Auth\DTOs;

class SendPasswordResetCodeDTO
{
    public function __construct(
        public string $email,
    ) {}
}
