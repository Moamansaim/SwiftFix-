<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Requests\RegisterRequest;
use App\Features\Auth\UseCases\LoginUser;
use App\Features\Auth\UseCases\LogOut;
use App\Features\Auth\UseCases\RegisterUser;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    public function __construct(
        public RegisterUser $registerUser,
        public LoginUser $loginUser,
        public LogOut $logOut,
    ) {}

    // تسجيل المستخدم
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

        return response()->json('تم التسجيل بنجاح', 201);
    }

    // تسجيل دخول المستخدم
    public function login(LoginRequest $loginRequest)
    {
        $request = new LoginUserDTO(
            $loginRequest->email,
            $loginRequest->password
        );

        $result = $this->loginUser->login($request);

        if (isset($result['success']) && $result['success'] === false) {
            return response()->json([
                'message' => $result['message'],
            ], 401);
        }

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح',
            'data' => $result
        ], 200);
    }

    // تسجيل خروج المستخدم
    public function logout()
    {
        $this->logOut->logout();
        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    // إعادة تعيين كلمة المرور
    public function restPassword() {}

    // التحقق من ايميل المستخدم
    public function verifyEmail() {}
}