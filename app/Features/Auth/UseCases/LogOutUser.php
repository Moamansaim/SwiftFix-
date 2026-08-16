<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\Interfaces\AuthRepositoryInterface;

class LogOutUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function logout()
    {
        return $this->authRepository->logout();
    }
}
