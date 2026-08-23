<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class LoginUser
{
    private const MAX_ATTEMPTS = 5;          // NFR-08
    private const LOCK_SECONDS = 60 * 15;    // 15 دقيقة

    public function __construct(
        private AuthRepositoryInterface $authRepository,
    ) {}

    public function login(LoginUserDTO $loginUserDTO): array
    {
        // المفتاح: البريد + الـ IP (حتى لا يتشارك مهاجمان نفس العداد)
        $throttleKey = strtolower($loginUserDTO->email).'|'.request()->ip();

        // 1) مقفول أصلاً؟ لا نفحص أي شيء — نرد 429 مع الثواني المتبقية
        if (RateLimiter::tooManyAttempts($throttleKey, self::MAX_ATTEMPTS)) {
            return [
                'error'   => true,
                'status'  => 429,
                'message' => 'محاولات فاشلة كثيرة. حاول مجدداً بعد '.RateLimiter::availableIn($throttleKey).' ثانية.',
            ];
        }

        $user = $this->authRepository->login($loginUserDTO);

        // 2) بيانات خاطئة → نضرب العداد ونرد رسالة عامة (ضد Enumeration)
        if (! $user || ! Hash::check($loginUserDTO->password, $user->password)) {
            RateLimiter::hit($throttleKey, self::LOCK_SECONDS);

            return [
                'error'   => true,
                'status'  => 401,
                'message' => 'بيانات تسجيل الدخول غير صحيحة',
            ];
        }

        // 3) البريد غير مفعّل (قاعدة مؤمن الأصلية — ما لمسناها)
        if (! $user->hasVerifiedEmail()) {
            return [
                'error'   => true,
                'status'  => 403,
                'message' => 'يرجى تفعيل البريد الإلكتروني قبل تسجيل الدخول',
            ];
        }

        // 4) حساب موقوف → 403 (أماننا)
        if ($user->isSuspended()) {
            return [
                'error'   => true,
                'status'  => 403,
                'message' => 'هذا الحساب موقوف. تواصل مع الدعم.',
            ];
        }

        // 5) نجاح → نصفر العداد ونصدر التوكن مع صلاحية حسب remember_me (ميزة مؤمن)
        RateLimiter::clear($throttleKey);

        $expiresAt = ($loginUserDTO->remember_me ?? false)
            ? now()->addDays(30)
            : now()->addHours(2);

        $token = $user->createToken(
            'auth-token',
            [],
            $expiresAt
        )->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
