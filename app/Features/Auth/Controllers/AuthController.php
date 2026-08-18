<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\DTOs\SendVerificationEmailDTO;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Requests\RegisterRequest;
use App\Features\Auth\Requests\ResetPasswordRequest;
use App\Features\Auth\Requests\SendPasswordResetCodeRequest;
use App\Features\Auth\Requests\SendVerificationEmailRequest;
use App\Features\Auth\Requests\VerifyEmailRequest;
use App\Features\Auth\Resources\UserResource;
use App\Features\Auth\UseCases\LoginUser;
use App\Features\Auth\UseCases\LogOutUser;
use App\Features\Auth\UseCases\RegisterUser;
use App\Features\Auth\UseCases\ResetPasswordUser;
use App\Features\Auth\UseCases\SendPasswordResetCode;
use App\Features\Auth\UseCases\SendVerificationEmail;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function __construct(
        public RegisterUser $registerUser,
        public LoginUser $loginUser,
        public LogOutUser $logOutUser,
        public SendPasswordResetCode $sendPasswordResetCode,
        public ResetPasswordUser $resetPasswordUser,
        public SendVerificationEmail $sendVerificationEmail,
    ) {}

    public function register(RegisterRequest $registerRequest)
    {
        $request = new RegisterUserDTO(
            $registerRequest->first_name,
            $registerRequest->last_name,
            $registerRequest->email,
            $registerRequest->phone_number,
            $registerRequest->password,
        );

        $this->registerUser->register($request);

        return response()->json([
            'message' => 'تم إنشاء الحساب بنجاح، يرجى التحقق من بريدك الإلكتروني لتفعيل الحساب',
        ], 201);
    }

    public function login(LoginRequest $loginRequest)
    {
        $request = new LoginUserDTO(
            $loginRequest->email,
            $loginRequest->password
        );

        $result = $this->loginUser->login($request);

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => new UserResource($result['user']),
            'token' => $result['token'],
        ], 200);
    }

    public function logout()
    {
        $user = $this->logOutUser->logout();

        if (isset($user['error']) && $user['error'] === true) {
            return response()->json([
                'message' => $user['message'],
            ], 401);
        }

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    public function sendPasswordResetCode(SendPasswordResetCodeRequest $sendPasswordResetCodeRequest)
    {
        $dto = new SendPasswordResetCodeDTO(
            $sendPasswordResetCodeRequest->email
        );

        $result = $this->sendPasswordResetCode->handle($dto);

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        $dto = new ResetPasswordDTO(
            $resetPasswordRequest->email,
            $resetPasswordRequest->code,
            $resetPasswordRequest->password,
        );

        $result = $this->resetPasswordUser->handle($dto);

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message'],
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    public function sendVerificationEmail(SendVerificationEmailRequest $request)
    {
        $dto = new SendVerificationEmailDTO($request->email);
        $result = $this->sendVerificationEmail->handle($dto);

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }

        return response()->json([
            'message' => $result['message'],
        ], 200);
    }

    public function verifyEmail(VerifyEmailRequest $request)
    {
        $result = $request->fulfill();

        if (isset($result['error']) && $result['error'] === true) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }

        return response()->json([
            'message' => 'تم تفعيل البريد الإلكتروني بنجاح',
        ], 200);
    }

    public function resendVerificationEmail(SendVerificationEmailRequest $request)
    {
        return $this->sendVerificationEmail($request);
    }
}