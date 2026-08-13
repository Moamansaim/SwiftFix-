<?php

namespace App\Features\Auth\InterFaces;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;

interface AuthRepositoryInterFace
{
    public function create(RegisterUserDTO $registerUserDTO);
    public function login(LoginUserDTO $loginUserDTO);
    public function logout();
}