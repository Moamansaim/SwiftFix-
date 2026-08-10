<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\DTOs\LoginUserDTO;
use App\Features\Auth\DTOs\RegisterUserDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterFace
{
    public function create(RegisterUserDTO $registerUserDTO)
    {
        $user_data = new User;
        $user_data->first_name = $registerUserDTO->first_name;
        $user_data->last_name = $registerUserDTO->last_name;
        $user_data->email = $registerUserDTO->email;
        $user_data->phone_number = $registerUserDTO->phone_number;
        $user_data->password = Hash::make($registerUserDTO->password);
        $user_data->save();
    }

    public function login(LoginUserDTO $loginUserDTO)
    {
        return User::where('email', $loginUserDTO->email)->first();
    }

    public function logout()
    {
        $user = Auth::guard('sanctum')->user();
        $user->currentAccessToken()->delete();
               
    }
}