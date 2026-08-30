<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class ResetPasswordUser
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function handle(ResetPasswordDTO $resetPasswordDTO): array
    {
        $user = $this->authRepository->findByEmail($resetPasswordDTO->email);

        if (! $user) {
            return [
                'error' => true,
                'message' => 'عذرا , البريد الإلكتروني غير موجود',
            ];
        }

        if (! Hash::check($resetPasswordDTO->code, $user->code) || now()->isAfter($user->code_expires_at)) {
            return [
                'error' => true,
                'message' => 'الكود غير صالح',
            ];
        }

        $this->authRepository->resetPassword($resetPasswordDTO, $user);

        return [
            'success' => true,
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح',
        ];
    }
}
