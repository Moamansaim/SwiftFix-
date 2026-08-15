<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\PasswordVerifyEmailUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\RestPasswordDTO;
use App\Features\Auth\Requests\LoginRequest;
use App\Features\Auth\Requests\PasswordVerifyEmailRequest;
use App\Features\Auth\Requests\RegisterRequest;
use App\Features\Auth\Requests\RestPasswordRequest;
use App\Features\Auth\Resources\UserResource;
use App\Features\Auth\UseCases\LoginUser;
use App\Features\Auth\UseCases\LogOutUser;
use App\Features\Auth\UseCases\PasswordVerifyEmail;
use App\Features\Auth\UseCases\RegisterUser;
use App\Features\Auth\UseCases\RestPasswordUser;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{
    public function __construct(
        public RegisterUser $registerUser,
        public LoginUser $loginUser,
        public LogOutUser $logOutUser,
        public PasswordVerifyEmail $passwordVerifyEmail,
        public RestPasswordUser $restPasswordUser
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

        return response()->json([
            'message' => 'تم تسجيل المستخدم بنجاح'
        ], 200);
    }

    // تسجيل دخول المستخدم
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

    // تسجيل خروج المستخدم
    public function logout()
    {
        $user =  $this->logOutUser->logout();

        if (isset($user['error']) && $user['error'] === true) {
            return response()->json([
                'message' => $user['message'],
            ], 401);
        }

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ], 200);
    }

    // التحقق من الايميل   
    public function passwordVerifyEmail(PasswordVerifyEmailRequest $passwordVerifyEmailRequest)
    {
        $request = new PasswordVerifyEmailUserDTO(
            $passwordVerifyEmailRequest->email
        );

        $user = $this->passwordVerifyEmail->passwordVerifyEmail($request);

        if (isset($user['error']) && $user['error'] === true) {

            return response()->json([
                'message' => $user['message']
            ], 422);
        }

        return response()->json([
            'message' => 'تم إرسال الكود بنجاح'
        ], 200);
    }

    // إعادة تعيين كلمة المرور
    public function restPassword(RestPasswordRequest $restPasswordRequest)
    {
        $request = new RestPasswordDTO(
            $restPasswordRequest->email,
            $restPasswordRequest->code,
            $restPasswordRequest->password,
        );

        $user = $this->restPasswordUser->restPassword($request);

        if (isset($user['error']) && $user['error'] === true) {

            return response()->json([
                'message' => $user['message']
            ], 422);
        }

        return response()->json([
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح'
        ], 200);
    }


    // التحقق من ايميل المستخدم
    public function verifyEmail() {}
}