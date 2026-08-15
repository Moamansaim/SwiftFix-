<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\InterFaces\SendEamilInterFace;
use App\Mail\SendCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendEamilRepository implements SendEamilInterFace
{
    public function sendCodeToEamil($user)
    {
        $code = random_int(10000, 99999);

        Mail::to($user->email)->send(new SendCode($user, $code));

        $user->code = Hash::make($code);
        $user->code_expires_at = now()->addMinutes(5);
        $user->save();
    }
}