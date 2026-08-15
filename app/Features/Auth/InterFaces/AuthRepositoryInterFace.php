<?php

namespace App\Features\Auth\InterFaces;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\PasswordVerifyEmailUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\RestPasswordDTO;
use App\Features\Auth\Models\User;

interface AuthRepositoryInterFace
{
    public function create(RegisterUserDTO $registerUserDTO);
    public function login(LoginUserDTO $loginUserDTO);
    public function logout();
    public function checkEmailToSendCode(PasswordVerifyEmailUserDTO $passwordVerifyEmailUserDTO);
    public function findEmail(RestPasswordDTO $restPasswordDTO);
    public function restPassword(RestPasswordDTO $restPasswordDTO, User $user);
}