<?php

namespace App\Features\Auth\UseCases;

use App\Features\Auth\DTOs\PasswordVerifyEmailUserDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use App\Features\Auth\InterFaces\SendEamilInterFace;



class PasswordVerifyEmail
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
        private SendEamilInterFace $sendEamilInterFace
    ) {}

    public function passwordVerifyEmail(PasswordVerifyEmailUserDTO $passwordVerifyEmailUserDTO)
    {
        $user =  $this->authRepositoryInterFace->checkEmailToSendCode($passwordVerifyEmailUserDTO);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'عذراً, الايميل غير موجود'
            ];
        }

        $this->sendEamilInterFace->sendCodeToEamil($user);
    }
}