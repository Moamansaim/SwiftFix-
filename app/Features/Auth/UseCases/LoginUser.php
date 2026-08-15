<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use Illuminate\Support\Facades\Hash;


class LoginUser
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
    ) {}

    public function login(LoginUserDTO $loginUserDTO)
    {
        $user = $this->authRepositoryInterFace->login($loginUserDTO);

        if (!$user || !Hash::check($loginUserDTO->password, $user->password)) {
            return [
                'error' => true,
                'message' => 'بيانات تسجيل الدخول غير صحيحة',
            ];
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}