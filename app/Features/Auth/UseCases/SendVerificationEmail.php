<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\SendVerificationEmailDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;

class SendVerificationEmail
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function handle(SendVerificationEmailDTO $sendVerificationEmailDTO): array
    {
        $user = $this->authRepository->findByEmail($sendVerificationEmailDTO->email);

        if (! $user) {
            return [
                'error' => true,
                'message' => 'عذراً , البريد الإلكتروني غير موجود',
            ];
        }

        if (! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();

            return [
                'success' => true,
                'message' => 'إذا كان البريد الإلكتروني مسجلاً وغير مفعّل، ستصلك رسالة تحتوي على رابط التفعيل',
            ];
        }

        return [
            'error' => true,
            'message' => 'بريدك الإلكتروني مفعل من قبل؟',
        ];
    }
}
