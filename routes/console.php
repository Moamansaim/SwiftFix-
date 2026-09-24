<?php

use App\Features\Auth\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Broadcast;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Broadcast::channel(
    'shop.{userId}',
    function (User $user, int $userId): bool {

        return $user->id === $userId;
    }
);