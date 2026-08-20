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
        Schema::create('shop_owner_verification_service', function (Blueprint $table) {
            $table->foreignId('shop_owner_verification_id')
                ->constrained('shop_owner_verifications', 'id', 'fk_shop_verification')
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services', 'id')
                ->restrictOnDelete();
            $table->primary(['shop_owner_verification_id', 'service_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_verification');
    }
};