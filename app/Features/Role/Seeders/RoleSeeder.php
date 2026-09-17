<?php

namespace App\Features\Role\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'shopOwner']);
        Role::create(['name' => 'customer']);
    }
}