<?php

namespace App\Features\Country\Seeders;

use App\Features\Country\Models\Country;
use Illuminate\Database\Seeder;

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

        foreach ($countries as $country) {
            Country::firstOrCreate([
                'name' => $country['name'],
            ]);
        }
    }
}