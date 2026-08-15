<?php

namespace App\Features\Auth\InterFaces;

use App\Features\Auth\Models\User;

interface SendEamilInterFace
{
    public function sendCodeToEamil(User $user);
}