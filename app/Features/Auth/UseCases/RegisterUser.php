<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;

class RegisterUser
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
    ) {}
    
    public function register(RegisterUserDTO $registerUserDTO) 
    {
        $this->authRepositoryInterFace->create($registerUserDTO);
    }
}