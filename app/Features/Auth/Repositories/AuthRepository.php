<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\PasswordVerifyEmailUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\RestPasswordDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterFace
{
    public function create(RegisterUserDTO $registerUserDTO)
    {
        $user = new User;
        $user->first_name = $registerUserDTO->first_name;
        $user->last_name = $registerUserDTO->last_name;
        $user->email = $registerUserDTO->email;
        $user->phone_number = $registerUserDTO->phone_number;
        $user->password = Hash::make($registerUserDTO->password);
        $user->save();
    }

    public function login(LoginUserDTO $loginUserDTO)
    {
        return User::where('email', $loginUserDTO->email)->first();
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return [
                'error' => true,
                'message' => 'المستخدم غير موجود',
            ];
        }

        $user->currentAccessToken()->delete();
    }

    public function checkEmailToSendCode(PasswordVerifyEmailUserDTO $passwordVerifyEmailUserDTO)
    {
        return User::where('email', $passwordVerifyEmailUserDTO->email)->first();
    }

    public function findEmail(RestPasswordDTO $restPasswordDTO)
    {
        return User::where('email', $restPasswordDTO->email)->first();
    }

    public function restPassword(RestPasswordDTO $restPasswordDTO, User $user)
    {
        $user->password = Hash::make($restPasswordDTO->password);
        $user->code = null;
        $user->code_expires_at = null;
        $user->save();
    }
}