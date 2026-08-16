<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;

class RegisterUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function register(RegisterUserDTO $registerUserDTO)
    {
        $this->authRepository->create($registerUserDTO);
    }
}
