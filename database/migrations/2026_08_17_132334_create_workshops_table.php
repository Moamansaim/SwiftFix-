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
        Schema::create('workshops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete(); // 1-to-1 with users
            $table->string('business_name');
            $table->text('description')->nullable();
            $table->json('supported_devices');   // ["laptops","tablets"]
            $table->json('working_hours');       // schedule object
            $table->decimal('latitude', 10, 8);  // GPS (FR-07/FR-17)
            $table->decimal('longitude', 11, 8);
            $table->json('verification_docs')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
                ->default('pending')->index();  // approval workflow (FR-24)
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workshops');
    }
};
