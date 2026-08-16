<?php

namespace App\Features\Auth\DTOs;

class SendVerificationEmailDTO
{
    public function __construct(
        public string $email,
    ) {}
}
