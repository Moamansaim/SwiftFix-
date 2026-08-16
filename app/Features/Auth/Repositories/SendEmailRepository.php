<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\Interfaces\SendEmailInterface;
use App\Features\Auth\Mail\SendCode;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SendEmailRepository implements SendEmailInterface
{
    public function sendResetCodeToEmail(User $user): void
    {
        $code = (string) random_int(100000, 999999);

        Mail::to($user->email)->send(new SendCode($user, $code));

        $user->code = Hash::make($code);
        $user->code_expires_at = now()->addMinutes(5);
        $user->save();
    }
}
