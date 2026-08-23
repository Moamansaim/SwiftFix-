<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Interfaces\SendEmailInterface;

class SendPasswordResetCode
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private SendEmailInterface $sendEmailRepository,
    ) {}

    public function handle(SendPasswordResetCodeDTO $sendPasswordResetCodeDTO): array
    {
        $user = $this->authRepository->findUserByEmailForResetCode($sendPasswordResetCodeDTO);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'عذراً , البريد الإلكتروني غير موجود',
            ];
        }

        if ($user) {
            $this->sendEmailRepository->sendResetCodeToEmail($user);
        }

        return [
            'success' => true,
            'message' => 'إذا كان البريد الإلكتروني مسجلاً، ستصلك رسالة تحتوي على كود إعادة التعيين',
        ];
    }
}