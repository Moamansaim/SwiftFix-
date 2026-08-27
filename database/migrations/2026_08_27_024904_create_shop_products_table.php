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
        Schema::create('shop_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')
                ->constrained('shops', 'id')
                ->cascadeOnDelete();
            $table->foreignId('product_id')
                ->constrained('products', 'id')
                ->cascadeOnDelete();
            $table->foreignId('device_model_id')
                ->nullable()
                ->constrained('device_models', 'id')
                ->nullOnDelete();
            $table->bigInteger('quantity')->unsigned();
            $table->decimal('price', 10, 2)->unsigned();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'out_of_stock'])
                ->default('available');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_products');
    }
};