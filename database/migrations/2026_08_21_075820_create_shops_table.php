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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->cascadeOnDelete();
            $table->string('shop_name');
            $table->text('description');
            $table->string('cover_image')
                ->nullable();
            $table->foreignId('country_id')
                ->constrained('countries', 'id');
            $table->foreignId('city_id')
                ->constrained('cities', 'id');
            $table->foreignId('district_id')
                ->constrained('districts', 'id');
            $table->string('street');
            $table->decimal('latitude', 10, 8)->comment('خط العرض');
            $table->decimal('longitude', 11, 8)->comment('خط الطول');
            $table->json('working_hours');
            $table->decimal('rating_average')
                ->nullable()
                ->default(0);
            $table->bigInteger('rating_count')
                ->nullable()
                ->default(0);
            $table->enum('status', ['active', 'closed', 'bloked'])
                ->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shpos');
    }
};