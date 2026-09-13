<?php

namespace App\Features\Services\Seeders;

use App\Features\Services\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // الهواتف الذكية
            ['service_name' => 'إصلاح الهواتف الذكية'],
            ['service_name' => 'تغيير شاشة الهاتف'],
            ['service_name' => 'تغيير بطارية الهاتف'],
            ['service_name' => 'إصلاح منفذ الشحن'],
            ['service_name' => 'إصلاح الكاميرا'],
            ['service_name' => 'إصلاح السماعة والميكروفون'],
            ['service_name' => 'إصلاح أزرار الهاتف'],
            ['service_name' => 'إصلاح أعطال الشبكة والاتصال'],
            ['service_name' => 'إصلاح أعطال النظام والبرمجيات'],
            ['service_name' => 'فتح قفل الهاتف'],
            ['service_name' => 'استعادة بيانات الهاتف'],

            // الأجهزة اللوحية
            ['service_name' => 'إصلاح الأجهزة اللوحية'],
            ['service_name' => 'تغيير شاشة الجهاز اللوحي'],
            ['service_name' => 'تغيير بطارية الجهاز اللوحي'],
            ['service_name' => 'إصلاح منفذ الشحن للجهاز اللوحي'],
            ['service_name' => 'إصلاح أعطال الجهاز اللوحي البرمجية'],

            // الحواسيب المحمولة
            ['service_name' => 'إصلاح الحواسيب المحمولة'],
            ['service_name' => 'إصلاح شاشة الحاسوب المحمول'],
            ['service_name' => 'تغيير بطارية الحاسوب المحمول'],
            ['service_name' => 'إصلاح لوحة المفاتيح'],
            ['service_name' => 'إصلاح لوحة اللمس'],
            ['service_name' => 'إصلاح منفذ الشحن والطاقة'],
            ['service_name' => 'إصلاح اللوحة الأم'],
            ['service_name' => 'ترقية ذاكرة RAM'],
            ['service_name' => 'تركيب أو ترقية SSD'],
            ['service_name' => 'تنظيف وصيانة نظام التبريد'],

            // الحواسيب المكتبية
            ['service_name' => 'إصلاح الحواسيب المكتبية'],
            ['service_name' => 'إصلاح اللوحة الأم للحاسوب'],
            ['service_name' => 'إصلاح مزود الطاقة'],
            ['service_name' => 'إصلاح وترقية كرت الشاشة'],
            ['service_name' => 'ترقية RAM للحاسوب'],
            ['service_name' => 'تركيب وترقية وحدات التخزين'],
            ['service_name' => 'تجميع الحواسيب'],

            // الشاشات والتلفزيونات
            ['service_name' => 'إصلاح شاشات الكمبيوتر'],
            ['service_name' => 'إصلاح أجهزة التلفزيون'],
            ['service_name' => 'تغيير لوحة الشاشة'],
            ['service_name' => 'إصلاح الإضاءة الخلفية'],
            ['service_name' => 'إصلاح أعطال الشاشة والصورة'],
            ['service_name' => 'إصلاح أعطال الصوت'],

            // أجهزة الألعاب
            ['service_name' => 'إصلاح أجهزة الألعاب'],
            ['service_name' => 'إصلاح PlayStation'],
            ['service_name' => 'إصلاح Xbox'],
            ['service_name' => 'إصلاح Nintendo'],
            ['service_name' => 'إصلاح أيدي التحكم'],
            ['service_name' => 'إصلاح منافذ أجهزة الألعاب'],
            ['service_name' => 'إصلاح أعطال أجهزة الألعاب البرمجية'],

            // الطابعات والماسحات
            ['service_name' => 'إصلاح الطابعات'],
            ['service_name' => 'صيانة الطابعات'],
            ['service_name' => 'إصلاح الماسحات الضوئية'],
            ['service_name' => 'إصلاح أعطال الحبر والطباعة'],

            // الشبكات والاتصالات
            ['service_name' => 'إصلاح أجهزة الراوتر'],
            ['service_name' => 'إصلاح أجهزة المودم'],
            ['service_name' => 'إعداد وصيانة الشبكات'],
            ['service_name' => 'إصلاح نقاط الوصول اللاسلكية'],
            ['service_name' => 'إصلاح أجهزة الشبكات'],

            // الكاميرات
            ['service_name' => 'إصلاح الكاميرات الرقمية'],
            ['service_name' => 'إصلاح كاميرات المراقبة'],
            ['service_name' => 'تركيب كاميرات المراقبة'],
            ['service_name' => 'صيانة أنظمة المراقبة'],

            // الساعات والأجهزة القابلة للارتداء
            ['service_name' => 'إصلاح الساعات الذكية'],
            ['service_name' => 'تغيير بطارية الساعة الذكية'],
            ['service_name' => 'إصلاح شاشة الساعة الذكية'],
            ['service_name' => 'إصلاح الأساور الذكية'],

            // السماعات والصوتيات
            ['service_name' => 'إصلاح السماعات'],
            ['service_name' => 'إصلاح سماعات Bluetooth'],
            ['service_name' => 'إصلاح سماعات الرأس'],
            ['service_name' => 'إصلاح مكبرات الصوت'],

            // الإكسسوارات
            ['service_name' => 'إصلاح إكسسوارات الهواتف'],
            ['service_name' => 'إصلاح الشواحن'],
            ['service_name' => 'إصلاح كابلات الشحن'],
            ['service_name' => 'إصلاح Power Bank'],
            ['service_name' => 'إصلاح محولات الطاقة'],

            // البرمجيات
            ['service_name' => 'تثبيت أنظمة التشغيل'],
            ['service_name' => 'إعادة تثبيت نظام التشغيل'],
            ['service_name' => 'تثبيت التعريفات'],
            ['service_name' => 'إزالة الفيروسات والبرمجيات الضارة'],
            ['service_name' => 'حل مشاكل البرامج والتطبيقات'],
            ['service_name' => 'استعادة الملفات والبيانات'],
            ['service_name' => 'تهيئة الأجهزة وإعدادها'],

            // صيانة عامة
            ['service_name' => 'الصيانة الدورية للأجهزة'],
            ['service_name' => 'تنظيف الأجهزة من الغبار'],
            ['service_name' => 'فحص وتشخيص الأعطال'],
            ['service_name' => 'استعادة البيانات'],
            ['service_name' => 'ترقية الأجهزة'],
            ['service_name' => 'تركيب قطع الغيار'],
            ['service_name' => 'فحص الأجهزة قبل الشراء'],
        ];

        Service::upsert(
            $services,
            ['service_name'],
            []
        );
    }
}
