<?php

namespace App\Features\Auth\Interfaces;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Models\User;

interface AuthRepositoryInterface
{
    public function create(RegisterUserDTO $registerUserDTO);

    public function login(LoginUserDTO $loginUserDTO);

    public function logout();

    public function findUserByEmailForResetCode(SendPasswordResetCodeDTO $sendPasswordResetCodeDTO);

    public function findByEmail(string $email);

    public function resetPassword(ResetPasswordDTO $resetPasswordDTO, User $user);


    //public function updatePassword(UpdatePasswordDTO $updatePasswordDTO);
}

