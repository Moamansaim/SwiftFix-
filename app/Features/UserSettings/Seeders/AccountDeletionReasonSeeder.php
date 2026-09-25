<?php

namespace App\Features\UserSettings\Seeders;

use App\Features\UserSettings\Models\AccountDeletionReason;
use Illuminate\Database\Seeder;

class AccountDeletionReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reasons = [
            'الموقع سيئ',
            'لم أجد ورشة مناسبة',
            'لم أجد الخدمة التي أبحث عنها',
            'لم أجد قطع الغيار التي أحتاجها',
            'التطبيق لا يعمل بشكل جيد',
            'واجهت مشاكل أثناء استخدام التطبيق',
            'لم أعد بحاجة إلى التطبيق',
            'سبب آخر',
        ];

        foreach ($reasons as $reason) {
            AccountDeletionReason::firstOrCreate([
                'reason' => $reason,
            ]);
        }
    }
}