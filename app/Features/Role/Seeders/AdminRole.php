<?php

namespace App\Features\Role\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminRole extends Seeder
{
    public function run(): void
    {
        $admin = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        $adminPermissions = [
            // Brand Permissions
            'عرض العلامات التجارية',
            'إضافة علامة تجارية',
            'تعديل علامة تجارية',
            'حذف علامة تجارية',

            // Category Permissions
            'عرض الفئات',
            'إضافة فئة',
            'تعديل الفئة',
            'حذف الفئة',

            // Country Permissions
            'عرض الدول',
            'إضافة دولة',
            'تعديل الدولة',
            'حذف الدولة',

            // City Permissions
            'عرض المدن',
            'إضافة مدينة',
            'تعديل المدينة',
            'حذف المدينة',

            // Complaint Permissions
            'عرض الشكاوى',
            'الرد على الشكاوى',
            'حذف الشكاوى',

            // Contact Message Permissions
            'عرض رسائل التواصل',
            'الرد على رسائل التواصل',
            'حذف رسائل التواصل',
          
            // Device Model Permissions
            'عرض الأجهزة',
            'إضافة جهاز',
            'تعديل جهاز',
            'حذف جهاز',
          
            // Review Permissions
            'حذف تعليق تقييم',
            'عرض تقييمات الورشة',
            

            // Role Permissions
            'عرض الأدوار',
            'إضافة دور',
            'تعديل دور',
            'حذف دور',
            'عرض صلاحيات الدور',
            'إضافة صلاحية للدور',
            'إزالة صلاحية من الدور',

            // Service Permissions
            'عرض الخدمات',
            'إضافة خدمة',
            'تعديل خدمة',
            'حذف خدمة',

            // Shop Owner Permissions
            'عرض أصحاب الورش',
            'تجميد حساب صاحب الورشة',
            'فك تجميد حساب صاحب الورشة',
            'حذف حساب صاحب الورشة',
            'إضافة طلب تحقق صاحب الورشة',
            'عرض طلبات تحقق أصحاب الورش',
            'الموافقة على طلب تحقق صاحب الورشة',
            'رفض طلب تحقق صاحب الورشة',
            'حذف طلب تحقق صاحب الورشة',
            'تعديل ملف الورشة',
            'عرض ملف الورشة',

          
            // Account Deletion Reason Permissions
            'تفعيل وإلغاء تفعيل أسباب حذف الحساب',
        ];

        $admin->syncPermissions($adminPermissions);

        Role::firstOrCreate([
            'name' => 'shopOwner',
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => 'customer',
            'guard_name' => 'web',
        ]);
    }
}