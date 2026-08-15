<?php

namespace App\Features\Auth\UseCases;


use App\Features\Auth\DTOs\RestPasswordDTO;
use App\Features\Auth\InterFaces\AuthRepositoryInterFace;
use Illuminate\Support\Facades\Hash;


class RestPasswordUser
{
    public function __construct(
        private AuthRepositoryInterFace $authRepositoryInterFace,
    ) {}

    public function restPassword(RestPasswordDTO $restPasswordDTO)
    {
        $user = $this->authRepositoryInterFace->findEmail($restPasswordDTO);

        if (!$user) {
            return [
                'error' => true,
                'message' => 'عذراً, الايميل غير موجود'
            ];
        }

        if (! Hash::check($restPasswordDTO->code, $user->code) || now()->isAfter($user->code_expires_at)) {
            return [
                'error' => true,
                'message' => 'الكود غير صالح '
            ];
        }

        $this->authRepositoryInterFace->restPassword($restPasswordDTO, $user);
    }
}