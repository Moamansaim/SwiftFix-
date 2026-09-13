<?php

namespace App\Features\Auth\Repositories;

use App\Features\Auth\Interfaces\SendEmailInterface;
use App\Features\Auth\Mail\SendCode;
use App\Features\Auth\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Email repository for authentication-related emails.
 *
 * Handles sending password reset codes to users
 * and storing the code securely with its expiration time.
 */
class SendEmailRepository implements SendEmailInterface
{
    /**
     * Send a password reset code to the user's email.
     *
     * Generates a random six-digit verification code,
     * sends it to the user's registered email address,
     * hashes the code, and stores it with an expiration time.
     *
     * @param User $user The user who requested the password reset.
     * @return void
     */
    public function sendResetCodeToEmail(User $user): void
    {
        // Generate a random six-digit password reset code.
        $code = (string) random_int(100000, 999999);

        // Send the reset code to the user's email address.
        Mail::to($user->email)->send(new SendCode($user, $code));

        // Hash the reset code before storing it in the database.
        $user->code = Hash::make($code);

        // Set the reset code expiration time to five minutes.
        $user->code_expires_at = now()->addMinutes(5);

        // Save the reset code and expiration time.
        $user->save();
    }
}