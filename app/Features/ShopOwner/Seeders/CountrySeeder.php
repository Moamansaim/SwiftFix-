<?php

namespace App\Features\ShopOwner\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'الأردن'],
            ['name' => 'الإمارات العربية المتحدة'],
            ['name' => 'البحرين'],
            ['name' => 'الجزائر'],
            ['name' => 'جيبوتي'],
            ['name' => 'السعودية'],
            ['name' => 'السودان'],
            ['name' => 'سوريا'],
            ['name' => 'الصومال'],
            ['name' => 'العراق'],
            ['name' => 'عُمان'],
            ['name' => 'فلسطين'],
            ['name' => 'قطر'],
            ['name' => 'جزر القمر'],
            ['name' => 'الكويت'],
            ['name' => 'لبنان'],
            ['name' => 'ليبيا'],
            ['name' => 'مصر'],
            ['name' => 'المغرب'],
            ['name' => 'موريتانيا'],
            ['name' => 'اليمن'],
            ['name' => 'تونس'],
        ];

        DB::table('countries')->upsert(
            $countries,
            ['name'],
            []
        );
    }
}