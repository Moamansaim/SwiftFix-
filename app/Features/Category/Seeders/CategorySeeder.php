<?php

namespace App\Features\Category\Seeders;

use App\Features\Category\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            // Spare Parts
            ['category_name' => 'شاشات'],
            ['category_name' => 'بطاريات'],
            ['category_name' => 'منافذ الشحن'],
            ['category_name' => 'لوحات أم'],
            ['category_name' => 'كاميرات'],
            ['category_name' => 'فلاشات الكاميرا'],
            ['category_name' => 'سماعات داخلية'],
            ['category_name' => 'مكبرات صوت'],
            ['category_name' => 'ميكروفونات'],
            ['category_name' => 'أزرار التشغيل'],
            ['category_name' => 'أزرار الصوت'],
            ['category_name' => 'أزرار الهوم'],
            ['category_name' => 'كابلات داخلية'],
            ['category_name' => 'فلاتات'],
            ['category_name' => 'موصلات'],
            ['category_name' => 'شرائح SIM'],
            ['category_name' => 'قارئات SIM'],
            ['category_name' => 'قارئات الذاكرة'],
            ['category_name' => 'هوائيات الشبكة'],
            ['category_name' => 'هوائيات Wi-Fi'],
            ['category_name' => 'هوائيات Bluetooth'],
            ['category_name' => 'مراوح التبريد'],
            ['category_name' => 'مشتتات الحرارة'],
            ['category_name' => 'معالجات'],
            ['category_name' => 'ذاكرة RAM'],
            ['category_name' => 'أقراص SSD'],
            ['category_name' => 'أقراص HDD'],
            ['category_name' => 'بطاقات الرسوميات'],
            ['category_name' => 'مزودات الطاقة'],
            ['category_name' => 'لوحات المفاتيح'],
            ['category_name' => 'لوحات اللمس'],
            ['category_name' => 'شواحن اللابتوب'],
            ['category_name' => 'مفصلات اللابتوب'],
            ['category_name' => 'بطاريات اللابتوب'],
            ['category_name' => 'شاشات اللابتوب'],
            ['category_name' => 'كاميرات اللابتوب'],
            ['category_name' => 'كابلات الشاشة'],
            ['category_name' => 'مراوح اللابتوب'],

            // Chargers
            ['category_name' => 'شواحن حائطية'],
            ['category_name' => 'شواحن سريعة'],
            ['category_name' => 'شواحن لاسلكية'],
            ['category_name' => 'شواحن سيارات'],
            ['category_name' => 'شواحن متعددة المنافذ'],
            ['category_name' => 'شواحن USB'],
            ['category_name' => 'شواحن USB-C'],
            ['category_name' => 'شواحن MagSafe'],
            ['category_name' => 'شواحن اللابتوب'],
            ['category_name' => 'محولات الطاقة'],

            // Cables
            ['category_name' => 'كابلات USB-C'],
            ['category_name' => 'كابلات Lightning'],
            ['category_name' => 'كابلات Micro USB'],
            ['category_name' => 'كابلات USB-A'],
            ['category_name' => 'كابلات HDMI'],
            ['category_name' => 'كابلات DisplayPort'],
            ['category_name' => 'كابلات Ethernet'],
            ['category_name' => 'كابلات الصوت'],
            ['category_name' => 'كابلات AUX'],
            ['category_name' => 'كابلات الطابعة'],
            ['category_name' => 'كابلات البيانات'],
            ['category_name' => 'كابلات الشحن'],

            // Accessories
            ['category_name' => 'حافظات الهواتف'],
            ['category_name' => 'حافظات اللابتوب'],
            ['category_name' => 'حافظات الأجهزة اللوحية'],
            ['category_name' => 'واقيات الشاشة'],
            ['category_name' => 'واقيات الكاميرا'],
            ['category_name' => 'حاملات الهواتف'],
            ['category_name' => 'حاملات السيارات'],
            ['category_name' => 'حاملات اللابتوب'],
            ['category_name' => 'حوامل الأجهزة اللوحية'],
            ['category_name' => 'لوحات مفاتيح خارجية'],
            ['category_name' => 'فأرات'],
            ['category_name' => 'فأرات لاسلكية'],
            ['category_name' => 'لوحات مفاتيح لاسلكية'],
            ['category_name' => 'USB Hubs'],
            ['category_name' => 'محولات USB'],
            ['category_name' => 'محولات HDMI'],
            ['category_name' => 'محولات الشبكة'],
            ['category_name' => 'قارئات البطاقات'],
            ['category_name' => 'ذاكرات USB'],
            ['category_name' => 'بطاقات الذاكرة'],

            // Audio
            ['category_name' => 'سماعات سلكية'],
            ['category_name' => 'سماعات لاسلكية'],
            ['category_name' => 'سماعات Bluetooth'],
            ['category_name' => 'سماعات رأس'],
            ['category_name' => 'سماعات أذن'],
            ['category_name' => 'مكبرات صوت Bluetooth'],
            ['category_name' => 'ميكروفونات'],
            ['category_name' => 'محولات الصوت'],
            ['category_name' => 'كابلات الصوت'],

            // Smart Watches
            ['category_name' => 'أحزمة الساعات الذكية'],
            ['category_name' => 'شواحن الساعات الذكية'],
            ['category_name' => 'شاشات الساعات الذكية'],
            ['category_name' => 'بطاريات الساعات الذكية'],
            ['category_name' => 'واقيات الساعات الذكية'],

            // Tablets
            ['category_name' => 'شاشات الأجهزة اللوحية'],
            ['category_name' => 'بطاريات الأجهزة اللوحية'],
            ['category_name' => 'منافذ شحن الأجهزة اللوحية'],
            ['category_name' => 'شاشات اللمس للأجهزة اللوحية'],
            ['category_name' => 'فلاتات الأجهزة اللوحية'],

            // Printers
            ['category_name' => 'خراطيش الحبر'],
            ['category_name' => 'أحبار الطابعات'],
            ['category_name' => 'أحبار الليزر'],
            ['category_name' => 'رؤوس الطباعة'],
            ['category_name' => 'وحدات الحبر'],
            ['category_name' => 'أسطوانات الطباعة'],
            ['category_name' => 'أحزمة الطابعات'],
            ['category_name' => 'قطع غيار الطابعات'],

            // Gaming
            ['category_name' => 'أذرع التحكم'],
            ['category_name' => 'أجهزة تحكم الألعاب'],
            ['category_name' => 'بطاريات أذرع التحكم'],
            ['category_name' => 'شواحن أذرع التحكم'],
            ['category_name' => 'سماعات الألعاب'],
            ['category_name' => 'لوحات مفاتيح الألعاب'],
            ['category_name' => 'فأرات الألعاب'],
            ['category_name' => 'كابلات أجهزة الألعاب'],
            ['category_name' => 'مراوح أجهزة الألعاب'],
            ['category_name' => 'قطع غيار أجهزة الألعاب'],

            // Networking
            ['category_name' => 'أجهزة الراوتر'],
            ['category_name' => 'أجهزة المودم'],
            ['category_name' => 'مقويات Wi-Fi'],
            ['category_name' => 'محولات الشبكة'],
            ['category_name' => 'Switches'],
            ['category_name' => 'Access Points'],
            ['category_name' => 'هوائيات الشبكة'],
            ['category_name' => 'كابلات الشبكة'],
            ['category_name' => 'موصلات الشبكة'],

            // Storage
            ['category_name' => 'أقراص SSD'],
            ['category_name' => 'أقراص HDD'],
            ['category_name' => 'أقراص NVMe'],
            ['category_name' => 'أقراص خارجية'],
            ['category_name' => 'فلاشات USB'],
            ['category_name' => 'بطاقات MicroSD'],
            ['category_name' => 'بطاقات SD'],

            // Repair Tools
            ['category_name' => 'مفكات صيانة'],
            ['category_name' => 'مفكات دقيقة'],
            ['category_name' => 'ملاقط صيانة'],
            ['category_name' => 'أدوات فتح الأجهزة'],
            ['category_name' => 'أجهزة قياس متعددة'],
            ['category_name' => 'كاوية لحام'],
            ['category_name' => 'محطات لحام'],
            ['category_name' => 'محطات هواء ساخن'],
            ['category_name' => 'أجهزة فصل الشاشات'],
            ['category_name' => 'أجهزة فصل الزجاج'],
            ['category_name' => 'أجهزة تنظيف الأجهزة'],
            ['category_name' => 'أجهزة اختبار البطاريات'],
            ['category_name' => 'أجهزة اختبار الشواحن'],
            ['category_name' => 'أجهزة اختبار USB'],

            // Cleaning & Maintenance
            ['category_name' => 'منظفات الشاشات'],
            ['category_name' => 'منظفات الأجهزة'],
            ['category_name' => 'كحول تنظيف إلكتروني'],
            ['category_name' => 'فرش تنظيف'],
            ['category_name' => 'مناديل تنظيف'],
            ['category_name' => 'هواء مضغوط'],
            ['category_name' => 'مواد إزالة اللاصق'],
            ['category_name' => 'لاصق الشاشات'],
            ['category_name' => 'لاصق البطاريات'],
            ['category_name' => 'شرائط لاصقة'],
            ['category_name' => 'معجون حراري'],

            // Other
            ['category_name' => 'محولات'],
            ['category_name' => 'وصلات'],
            ['category_name' => 'قطع إلكترونية'],
            ['category_name' => 'مكونات إلكترونية'],
            ['category_name' => 'منتجات عامة'],
            ['category_name' => 'مستلزمات أخرى'],
        ];

        $categories = collect($categories)
            ->unique('category_name')
            ->values()
            ->toArray();

        Category::upsert(
            $categories,
            ['category_name'],
            []
        );
    }
}
