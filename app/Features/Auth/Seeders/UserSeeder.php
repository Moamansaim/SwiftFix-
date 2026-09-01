<?php

namespace App\Features\Auth\Seeders;

use App\Features\Auth\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;
    public function run(): void
    {
        User::create(
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@example.com',
                'phone_number' => '+972595121939',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );
    }
}
