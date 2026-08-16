<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\DTOs\ResetPasswordDTO;
use App\Features\Auth\DTOs\SendPasswordResetCodeDTO;
use App\Features\Auth\Interfaces\AuthRepositoryInterface;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
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

        $user->sendEmailVerificationNotification();

        return $user;
    }

    public function login(LoginUserDTO $loginUserDTO)
    {
        return User::where('email', $loginUserDTO->email)->first();
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();

        if (! $user) {
            return [
                'error' => true,
                'message' => 'المستخدم غير موجود',
            ];
        }

        $user->currentAccessToken()->delete();
    }

    public function findUserByEmailForResetCode(SendPasswordResetCodeDTO $sendPasswordResetCodeDTO)
    {
        return User::where('email', $sendPasswordResetCodeDTO->email)->first();
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function resetPassword(ResetPasswordDTO $resetPasswordDTO, User $user)
    {
        $user->password = Hash::make($resetPasswordDTO->password);
        $user->code = null;
        $user->code_expires_at = null;
        $user->save();
    }
}
