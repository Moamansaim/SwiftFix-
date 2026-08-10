<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\InterFaces\AuthRepositoryInterFace;

class LogOut
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
    ) {}
    
    public function logout() 
    {
        $this->authRepositoryInterFace->logout();
    }
}