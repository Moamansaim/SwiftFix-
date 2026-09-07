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
                ->unique()
                ->constrained('users', 'id')
                ->cascadeOnDelete();
            $table->string('shop_name')
                ->nullable();
            $table->text('description')
                ->nullable();
            $table->string('cover_image')
                ->nullable();
            $table->string('commercial_record_image')
                ->nullable();
            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries', 'id')
                ->nullOnDelete();
            $table->foreignId('city_id')
                ->nullable()
                ->constrained('cities', 'id')
                ->nullOnDelete();
            $table->string('district')
                ->nullable();
            $table->string('street')
                ->nullable();
            $table->decimal('latitude', 10, 8)
                ->nullable()
                ->comment('خط العرض');
            $table->decimal('longitude', 11, 8)
                ->nullable()
                ->comment('خط الطول');
            $table->json('working_hours')
                ->nullable();
            $table->decimal('rating_average')
                ->default(0);
            $table->unsignedBigInteger('rating_count')
                ->default(0);
            $table->enum('status', [
                'blocked',
                'open',
                'closed',
            ])->default('closed');
            $table->boolean('is_verified')
                ->nullable()
                ->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};