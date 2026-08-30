```php
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignId('category_id')
                ->constrained('categories', 'id')
                ->cascadeOnDelete();
            $table->foreignId('device_model_id')
                ->nullable()
                ->constrained('device_models', 'id')
                ->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique([
                'product_name',
                'category_id',
                'device_model_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
