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
        Schema::create('service_shop', function (Blueprint $table) {
            $table->foreignId('shop_id')
                ->constrained('shops', 'id')
                ->cascadeOnDelete();
            $table->foreignId('service_id')
                ->constrained('services', 'id')
                ->cascadeOnDelete();
            $table->primary(['shop_id', 'service_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_shop');
    }
};