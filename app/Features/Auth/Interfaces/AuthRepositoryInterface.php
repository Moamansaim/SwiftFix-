<?php

namespace App\Features\Auth\Interfaces;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Models\User;

interface AuthRepositoryInterface
{
    public function create(RegisterUserDTO $registerUserDTO): User;

    public function login(LoginUserDTO $loginUserDTO): ?User;

    public function logout();

    public function findUserByEmailForResetCode(SendPasswordResetCodeDTO $sendPasswordResetCodeDTO): ?User;

    public function findByEmail(string $email): ?User;

    public function resetPassword(ResetPasswordDTO $resetPasswordDTO, User $user): void;

    public function changePassword(ChangePasswordDTO $changePasswordDTO, User $user): void;
}
