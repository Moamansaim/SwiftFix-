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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 30);   // الاسم الاول
            $table->string('last_name', 40);    // الاسم الثاني
            $table->string('email')->unique(); // الايميل
            $table->string('phone_number', 16)->unique();
            $table->string('code')->nullable(); // كود التحقق من نسيان كلمة المرور
            $table->timestamp('code_expires_at')->nullable(); // تاريخ انتهاء صلاحية كود التحقق من نسيان كلمة المرور     
            $table->timestamp('email_verified_at')->nullable(); // حقل التحقق من تأكيد الايميل
            $table->string('password'); // كلمة المرور
            $table->string('city', 100)->nullable();
            $table->enum('status', ['active', 'suspended'])->default('active'); // حالة الحساب :   فعال, مغلق 
            $table->rememberToken();  // تذكرني
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};