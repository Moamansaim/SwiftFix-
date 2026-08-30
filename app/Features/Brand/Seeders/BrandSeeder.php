<?php

namespace App\Features\Brand\Seeders;

use App\Features\Brand\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [

            // الهواتف الذكية والأجهزة اللوحية
            ['brand_name' => 'آبل'],
            ['brand_name' => 'سامسونج'],
            ['brand_name' => 'شاومي'],
            ['brand_name' => 'هواوي'],
            ['brand_name' => 'هونر'],
            ['brand_name' => 'أوبو'],
            ['brand_name' => 'فيفو'],
            ['brand_name' => 'ون بلس'],
            ['brand_name' => 'ريلمي'],
            ['brand_name' => 'موتورولا'],
            ['brand_name' => 'نوكيا'],
            ['brand_name' => 'سوني'],
            ['brand_name' => 'إل جي'],
            ['brand_name' => 'إتش تي سي'],
            ['brand_name' => 'زد تي إي'],
            ['brand_name' => 'تي سي إل'],
            ['brand_name' => 'ألكاتيل'],
            ['brand_name' => 'أسوس'],
            ['brand_name' => 'لينوفو'],
            ['brand_name' => 'تكنو'],
            ['brand_name' => 'إنفينيكس'],
            ['brand_name' => 'إيتل'],
            ['brand_name' => 'ناثينج'],
            ['brand_name' => 'ميزو'],
            ['brand_name' => 'بلاك بيري'],
            ['brand_name' => 'شارب'],
            ['brand_name' => 'باناسونيك'],

            // أجهزة الكمبيوتر واللابتوب
            ['brand_name' => 'ديل'],
            ['brand_name' => 'إتش بي'],
            ['brand_name' => 'أيسر'],
            ['brand_name' => 'إم إس آي'],
            ['brand_name' => 'مايكروسوفت'],
            ['brand_name' => 'ريزر'],
            ['brand_name' => 'جيجابايت'],
            ['brand_name' => 'فوجيتسو'],
            ['brand_name' => 'توشيبا'],
            ['brand_name' => 'ميديون'],
            ['brand_name' => 'فريمورك'],
            ['brand_name' => 'ألين وير'],

            // أجهزة التلفاز والشاشات
            ['brand_name' => 'هايسنس'],
            ['brand_name' => 'فيزيو'],
            ['brand_name' => 'سكاي وورث'],
            ['brand_name' => 'هاير'],
            ['brand_name' => 'جي في سي'],
            ['brand_name' => 'فيستل'],
            ['brand_name' => 'هيتاشي'],
            ['brand_name' => 'جروندج'],

            // الكاميرات
            ['brand_name' => 'كانون'],
            ['brand_name' => 'نيكون'],
            ['brand_name' => 'فوجي فيلم'],
            ['brand_name' => 'أوليمبوس'],
            ['brand_name' => 'أوم سيستم'],
            ['brand_name' => 'لايكا'],
            ['brand_name' => 'جو برو'],
            ['brand_name' => 'دي جي آي'],
            ['brand_name' => 'ريكوه'],
            ['brand_name' => 'بنتاكس'],

            // الطابعات والماسحات الضوئية
            ['brand_name' => 'إبسون'],
            ['brand_name' => 'براذر'],
            ['brand_name' => 'ليكس مارك'],
            ['brand_name' => 'زيروكس'],
            ['brand_name' => 'كيوسيرا'],
            ['brand_name' => 'كونيكا مينولتا'],

            // أجهزة الألعاب
            ['brand_name' => 'مايكروسوفت'],
            ['brand_name' => 'نينتندو'],
            ['brand_name' => 'فالف'],

            // الساعات الذكية والأجهزة القابلة للارتداء
            ['brand_name' => 'جارمن'],
            ['brand_name' => 'فيتبيت'],
            ['brand_name' => 'أمازفيت'],
            ['brand_name' => 'بولار'],
            ['brand_name' => 'كاسيو'],

            // المنزل الذكي والأجهزة المنزلية
            ['brand_name' => 'أمازون'],
            ['brand_name' => 'جوجل'],
            ['brand_name' => 'رينج'],
            ['brand_name' => 'فيليبس'],
            ['brand_name' => 'دايسون'],
            ['brand_name' => 'بوش'],
            ['brand_name' => 'سيمنز'],
            ['brand_name' => 'ميلي'],
            ['brand_name' => 'إلكترولوكس'],
            ['brand_name' => 'ويرلبول'],
            ['brand_name' => 'ميديا'],
            ['brand_name' => 'جري'],
            ['brand_name' => 'أرشيليك'],
            ['brand_name' => 'بيكو'],
            ['brand_name' => 'ميتسوبيشي إلكتريك'],
            ['brand_name' => 'إن إي سي'],
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
