<?php

namespace App\Features\Brand\Seeders;

use App\Features\Brand\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            // Smartphones & Tablets
            ['brand_name' => 'Apple'],
            ['brand_name' => 'Samsung'],
            ['brand_name' => 'Xiaomi'],
            ['brand_name' => 'Huawei'],
            ['brand_name' => 'Honor'],
            ['brand_name' => 'Oppo'],
            ['brand_name' => 'Vivo'],
            ['brand_name' => 'OnePlus'],
            ['brand_name' => 'Realme'],
            ['brand_name' => 'Motorola'],
            ['brand_name' => 'Nokia'],
            ['brand_name' => 'Sony'],
            ['brand_name' => 'LG'],
            ['brand_name' => 'HTC'],
            ['brand_name' => 'ZTE'],
            ['brand_name' => 'TCL'],
            ['brand_name' => 'Alcatel'],
            ['brand_name' => 'Asus'],
            ['brand_name' => 'Lenovo'],
            ['brand_name' => 'Tecno'],
            ['brand_name' => 'Infinix'],
            ['brand_name' => 'Itel'],
            ['brand_name' => 'Nothing'],
            ['brand_name' => 'Meizu'],
            ['brand_name' => 'BlackBerry'],
            ['brand_name' => 'Sharp'],
            ['brand_name' => 'Panasonic'],

            // Laptops & Computers
            ['brand_name' => 'Dell'],
            ['brand_name' => 'HP'],
            ['brand_name' => 'Acer'],
            ['brand_name' => 'MSI'],
            ['brand_name' => 'Microsoft'],
            ['brand_name' => 'Razer'],
            ['brand_name' => 'Gigabyte'],
            ['brand_name' => 'Fujitsu'],
            ['brand_name' => 'Toshiba'],
            ['brand_name' => 'Medion'],
            ['brand_name' => 'Framework'],
            ['brand_name' => 'Alienware'],

            // TVs & Displays
            ['brand_name' => 'Hisense'],
            ['brand_name' => 'Vizio'],
            ['brand_name' => 'Skyworth'],
            ['brand_name' => 'Haier'],
            ['brand_name' => 'JVC'],
            ['brand_name' => 'Vestel'],
            ['brand_name' => 'Hitachi'],
            ['brand_name' => 'Grundig'],

            // Cameras
            ['brand_name' => 'Canon'],
            ['brand_name' => 'Nikon'],
            ['brand_name' => 'Fujifilm'],
            ['brand_name' => 'Olympus'],
            ['brand_name' => 'OM System'],
            ['brand_name' => 'Leica'],
            ['brand_name' => 'GoPro'],
            ['brand_name' => 'DJI'],
            ['brand_name' => 'Ricoh'],
            ['brand_name' => 'Pentax'],

            // Printers & Scanners
            ['brand_name' => 'Epson'],
            ['brand_name' => 'Brother'],
            ['brand_name' => 'Lexmark'],
            ['brand_name' => 'Xerox'],
            ['brand_name' => 'Kyocera'],
            ['brand_name' => 'Konica Minolta'],

            // Gaming Consoles
            ['brand_name' => 'Microsoft'],
            ['brand_name' => 'Nintendo'],
            ['brand_name' => 'Valve'],

            // Smart Watches & Wearables
            ['brand_name' => 'Garmin'],
            ['brand_name' => 'Fitbit'],
            ['brand_name' => 'Amazfit'],
            ['brand_name' => 'Polar'],
            ['brand_name' => 'Casio'],

            // Smart Home & Appliances
            ['brand_name' => 'Amazon'],
            ['brand_name' => 'Google'],
            ['brand_name' => 'Ring'],
            ['brand_name' => 'Philips'],
            ['brand_name' => 'Dyson'],
            ['brand_name' => 'Bosch'],
            ['brand_name' => 'Siemens'],
            ['brand_name' => 'Miele'],
            ['brand_name' => 'Electrolux'],
            ['brand_name' => 'Whirlpool'],
            ['brand_name' => 'Midea'],
            ['brand_name' => 'Gree'],
            ['brand_name' => 'Arçelik'],
            ['brand_name' => 'Beko'],
            ['brand_name' => 'Mitsubishi Electric'],
            ['brand_name' => 'NEC'],
        ];

        $brands = collect($brands)
            ->unique('brand_name')
            ->values()
            ->toArray();

        Brand::upsert(
            $brands,
            ['brand_name'],
            []
        );
    }
}