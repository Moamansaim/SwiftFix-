<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shop_owner_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');  // الاسم الاول
            $table->string('last_name');   // الاسم الثاني
            $table->string('email')->unique();
            $table->string('national_id_image');  // صورة  هوية صاحب المحل
            $table->string('phone_number', 16)->unique();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // حالة  الطلب : قيد الانتظار , موافق , رفض
            $table->foreignId('country_id')
                ->constrained('countries', 'id');
            $table->foreignId('reviewed_by') // تمت الموافقة من قبل
                ->nullable()
                ->constrained('users', 'id');
            $table->timestamp('reviewed_at')  // تاريخ الموافقة
                ->nullable();
            $table->text('notes')   // ملاحظة
                ->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_owner_verifications');
    }
};
