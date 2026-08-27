<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class LoginUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function login(LoginUserDTO $loginUserDTO)
    {
        $user = $this->authRepository->login($loginUserDTO);

        if (! $user || ! Hash::check($loginUserDTO->password, $user->password)) {
            return [
                'error' => true,
                'message' => 'بيانات تسجيل الدخول غير صحيحة',
            ];
        }

        if (! $user->hasVerifiedEmail()) {
            return [
                'error' => true,
                'message' => 'يرجى تفعيل البريد الإلكتروني قبل تسجيل الدخول',
            ];
        }

        $expiresAt = $loginUserDTO->remember_me
            ? now()->addDays(30)
            : now()->addHours(2);

        $token = $user->createToken(
            'auth-token',
            [],
            $expiresAt
        )->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
