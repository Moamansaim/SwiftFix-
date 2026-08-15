<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\InterFaces\AuthRepositoryInterFace;

class LogOutUser
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
    ) {}
    
    public function logout() 
    {
       return $this->authRepositoryInterFace->logout();
    }
}