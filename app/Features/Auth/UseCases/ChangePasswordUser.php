<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\ChangePasswordDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ChangePasswordUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function changePassword(ChangePasswordDTO $changePasswordDTO)
    {
        $user = Auth::guard('sanctum')->user();

        if (! $user) {
            return [
                'error' => true,
                'message' => 'غير مصرح لك بالوصول. يرجى تسجيل الدخول أولاً.',
            ];
        }

        $this->authRepository->changePassword($changePasswordDTO, $user);
    }
}