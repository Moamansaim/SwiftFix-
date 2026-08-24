<?php

namespace App\Features\Auth\Interfaces;

use App\Features\Auth\Models\User;

interface SendEmailInterface
{
    public function sendResetCodeToEmail(User $user): void;
}
