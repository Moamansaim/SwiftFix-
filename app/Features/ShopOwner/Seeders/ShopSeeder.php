<?php

namespace App\Features\ShopOwner\Seeders;

use App\Features\Auth\Models\User;
use App\Features\City\Models\City;
use App\Features\Country\Models\Country;
use App\Features\Services\Models\Service;
use App\Features\ShopOwner\Models\Shop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ShopSeeder extends Seeder
{
    /**
     * Seed twenty verified shops with owners,
     * locations, services, cover images and working hours.
     */
    public function run(): void
    {
        DB::transaction(function () {

            /*
            |--------------------------------------------------------------------------
            | Get existing data
            |--------------------------------------------------------------------------
            */

            $country = Country::first();

            $cities = City::query()
                ->where('country_id', $country?->id)
                ->get();

            $services = Service::query()->get();

            /*
            |--------------------------------------------------------------------------
            | Validate required data
            |--------------------------------------------------------------------------
            */

            if (!$country) {
                throw new \RuntimeException(
                    'لا توجد دولة في جدول countries.'
                );
            }

            if ($cities->isEmpty()) {
                throw new \RuntimeException(
                    'لا توجد مدن في جدول cities للدولة المحددة.'
                );
            }

            if ($services->isEmpty()) {
                throw new \RuntimeException(
                    'لا توجد خدمات في جدول services.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Get existing shop cover images
            |--------------------------------------------------------------------------
            |
            | Images are already stored using the UploadImage trait
            | inside:
            |
            | storage/app/public/shop-owner/cover-image-profile
            |
            */

            $coverImages = Storage::disk('public')->files(
                'shop-owner/cover-image-profile'
            );

            if (empty($coverImages)) {
                throw new \RuntimeException(
                    'لا توجد صور للورش في storage/app/public/shop-owner/cover-image-profile.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Shops data
            |--------------------------------------------------------------------------
            |
            | All shops are located in and around Deir al-Balah.
            | The coordinates are intentionally distributed around:
            |
            | Latitude:  31.420580883899166
            | Longitude: 34.348137742458206
            |
            | This makes them suitable for testing the map search
            | and distance calculation.
            |
            */

            $shops = [

                [
                    'shop_name' => 'ورشة الأقصى للسيارات',
                    'description' => 'ورشة متخصصة في صيانة السيارات والفحص والإصلاحات العامة.',
                    'district' => 'دير البلح',
                    'street' => 'شارع صلاح الدين',
                    'latitude' => 31.420650,
                    'longitude' => 34.348072,
                ],

                [
                    'shop_name' => 'ورشة المدينة الحديثة',
                    'description' => 'خدمات صيانة شاملة للسيارات مع فحص الأعطال وإصلاحها.',
                    'district' => 'دير البلح',
                    'street' => 'شارع البحر',
                    'latitude' => 31.418900,
                    'longitude' => 34.350800,
                ],

                [
                    'shop_name' => 'ورشة النور للميكانيكا',
                    'description' => 'صيانة ميكانيكية وكهربائية لمختلف أنواع السيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع البركة',
                    'latitude' => 31.423500,
                    'longitude' => 34.344500,
                ],

                [
                    'shop_name' => 'ورشة السرعة',
                    'description' => 'صيانة سريعة وفحص شامل للسيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع السوق',
                    'latitude' => 31.415800,
                    'longitude' => 34.352500,
                ],

                [
                    'shop_name' => 'ورشة المحترفين',
                    'description' => 'فريق متخصص في الصيانة الميكانيكية والكهربائية.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الشهداء',
                    'latitude' => 31.425200,
                    'longitude' => 34.351800,
                ],

                [
                    'shop_name' => 'ورشة التقنية للسيارات',
                    'description' => 'تشخيص أعطال السيارات باستخدام أجهزة الفحص الحديثة.',
                    'district' => 'دير البلح',
                    'street' => 'شارع المدرسة',
                    'latitude' => 31.417200,
                    'longitude' => 34.340500,
                ],

                [
                    'shop_name' => 'ورشة الأمان',
                    'description' => 'صيانة دورية وإصلاح أعطال السيارات بمختلف أنواعها.',
                    'district' => 'دير البلح',
                    'street' => 'شارع أبو حسني',
                    'latitude' => 31.429000,
                    'longitude' => 34.346500,
                ],

                [
                    'shop_name' => 'ورشة أبو محمد',
                    'description' => 'صيانة ميكانيكية وكهربائية وخدمات تبديل القطع.',
                    'district' => 'دير البلح',
                    'street' => 'شارع البلدية',
                    'latitude' => 31.411500,
                    'longitude' => 34.346000,
                ],

                [
                    'shop_name' => 'ورشة البراق',
                    'description' => 'متخصصة في صيانة المحركات وأنظمة السيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع القرية',
                    'latitude' => 31.432000,
                    'longitude' => 34.353000,
                ],

                [
                    'shop_name' => 'ورشة الشروق',
                    'description' => 'خدمات صيانة السيارات والفحص الدوري وإصلاح الأعطال.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الوادي',
                    'latitude' => 31.405500,
                    'longitude' => 34.351000,
                ],

                [
                    'shop_name' => 'ورشة الصفا',
                    'description' => 'ورشة متخصصة في الكهرباء والميكانيكا وصيانة السيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع صلاح الدين الجنوبي',
                    'latitude' => 31.399500,
                    'longitude' => 34.345500,
                ],

                [
                    'shop_name' => 'ورشة البركة',
                    'description' => 'خدمات متكاملة لصيانة السيارات وإصلاح الأعطال.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الساحل',
                    'latitude' => 31.436500,
                    'longitude' => 34.348500,
                ],

                [
                    'shop_name' => 'ورشة السلام',
                    'description' => 'صيانة ميكانيكية وكهربائية لجميع أنواع المركبات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع النخيل',
                    'latitude' => 31.408000,
                    'longitude' => 34.360000,
                ],

                [
                    'shop_name' => 'ورشة القدس',
                    'description' => 'خدمات فحص وصيانة السيارات وإصلاح الأعطال.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الشهداء',
                    'latitude' => 31.430500,
                    'longitude' => 34.361500,
                ],

                [
                    'shop_name' => 'ورشة المميز',
                    'description' => 'صيانة احترافية وفحص إلكتروني للسيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع المركز',
                    'latitude' => 31.414000,
                    'longitude' => 34.337500,
                ],

                [
                    'shop_name' => 'ورشة الخبراء',
                    'description' => 'إصلاح السيارات وخدمات الكهرباء والميكانيكا.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الجامعة',
                    'latitude' => 31.439000,
                    'longitude' => 34.355000,
                ],

                [
                    'shop_name' => 'ورشة الخليج',
                    'description' => 'خدمات صيانة دورية وإصلاح أعطال السيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع البحر',
                    'latitude' => 31.402500,
                    'longitude' => 34.334500,
                ],

                [
                    'shop_name' => 'ورشة الأمل',
                    'description' => 'ورشة متخصصة في فحص وإصلاح السيارات.',
                    'district' => 'دير البلح',
                    'street' => 'شارع السوق القديم',
                    'latitude' => 31.444000,
                    'longitude' => 34.345000,
                ],

                [
                    'shop_name' => 'ورشة الشامل',
                    'description' => 'صيانة شاملة للسيارات مع توفر قطع الغيار.',
                    'district' => 'دير البلح',
                    'street' => 'شارع الميناء',
                    'latitude' => 31.393500,
                    'longitude' => 34.350000,
                ],

                [
                    'shop_name' => 'ورشة الأصالة',
                    'description' => 'صيانة السيارات وإصلاح الأعطال الميكانيكية والكهربائية.',
                    'district' => 'دير البلح',
                    'street' => 'شارع عين الحلوة',
                    'latitude' => 31.447500,
                    'longitude' => 34.362000,
                ],

            ];

            /*
            |--------------------------------------------------------------------------
            | Create shops
            |--------------------------------------------------------------------------
            */

            foreach ($shops as $index => $shopData) {

                /*
                |--------------------------------------------------------------------------
                | Create shop owner
                |--------------------------------------------------------------------------
                */
                $user = User::create([
                    'first_name' => 'Deir Balah',

                    'last_name' => 'Shop Owner ' . ($index + 1),

                    'email' => 'deirbalah.shop'
                        . ($index + 1)
                        . '@swiftfix.test',

                    /*
    |--------------------------------------------------------------------------
    | Unique phone number
    |--------------------------------------------------------------------------
    |
    | Use a different phone range from the previous seeder.
    |
    */

                    'phone_number' => '0568' . str_pad(
                        $index + 1,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),

                    'password' => Hash::make('password'),

                    'email_verified_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Assign shop owner role
                |--------------------------------------------------------------------------
                */

                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('shopOwner');
                }

                /*
                |--------------------------------------------------------------------------
                | Select city
                |--------------------------------------------------------------------------
                */

                $city = $cities[$index % $cities->count()];

                /*
                |--------------------------------------------------------------------------
                | Select random cover image
                |--------------------------------------------------------------------------
                */

                $coverImage = fake()->randomElement(
                    $coverImages
                );

                /*
                |--------------------------------------------------------------------------
                | Create shop
                |--------------------------------------------------------------------------
                */

                $shop = Shop::create([
                    'user_id' => $user->id,

                    'shop_name' => $shopData['shop_name'],

                    'description' => $shopData['description'],

                    /*
                    |--------------------------------------------------------------------------
                    | Example:
                    | shop-owner/cover-image-profile/filename.jpg
                    |--------------------------------------------------------------------------
                    */

                    'cover_image' => $coverImage,

                    'commercial_record_image' => null,

                    'country_id' => $country->id,

                    'city_id' => $city->id,

                    'district' => $shopData['district'],

                    'street' => $shopData['street'],

                    'latitude' => $shopData['latitude'],

                    'longitude' => $shopData['longitude'],

                    /*
                    |--------------------------------------------------------------------------
                    | Shop status
                    |--------------------------------------------------------------------------
                    |
                    | Alternate between open and closed.
                    | Both statuses are allowed in SearchShopMap.
                    |
                    */

                    'status' => $index % 2 === 0
                        ? 'open'
                        : 'closed',

                    /*
                    |--------------------------------------------------------------------------
                    | Working hours
                    |--------------------------------------------------------------------------
                    */

                    'working_hours' => [

                        [
                            'day' => 'monday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'tuesday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'wednesday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'thursday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'friday',
                            'from' => '14:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'saturday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                        [
                            'day' => 'sunday',
                            'from' => '08:00',
                            'to' => '18:00',
                        ],

                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | All seeded shops are verified
                    |--------------------------------------------------------------------------
                    */

                    'is_verified' => true,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Attach services
                |--------------------------------------------------------------------------
                |
                | Every shop gets service ID 41 if it exists.
                | Additional services are selected randomly.
                |
                */

                $selectedServices = $services
                    ->shuffle()
                    ->take(
                        min(2, $services->count())
                    )
                    ->pluck('id')
                    ->toArray();

                /*
                |--------------------------------------------------------------------------
                | Make sure service ID 41 exists on every shop
                |--------------------------------------------------------------------------
                */

                if ($services->contains('id', 41)) {
                    $selectedServices[] = 41;
                }

                /*
                |--------------------------------------------------------------------------
                | Remove duplicate service IDs
                |--------------------------------------------------------------------------
                */

                $selectedServices = array_unique(
                    $selectedServices
                );

                /*
                |--------------------------------------------------------------------------
                | Attach services with prices
                |--------------------------------------------------------------------------
                */

                foreach ($selectedServices as $serviceId) {

                    $shop->services()->attach(
                        $serviceId,
                        [
                            'price' => fake()->numberBetween(
                                50,
                                500
                            ),
                        ]
                    );
                }
            }
        });
    }
}