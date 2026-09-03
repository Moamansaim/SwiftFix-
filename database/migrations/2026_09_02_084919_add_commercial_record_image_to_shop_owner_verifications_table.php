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
        Schema::table('shop_owner_verifications', function (Blueprint $table) {
            $table->string('commercial_record_image')
                ->nullable()
                ->after('national_id_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_owner_verifications', function (Blueprint $table) {
            Schema::table('shop_owner_verifications', function (Blueprint $table) {
                $table->dropColumn('commercial_record_image');
            });
        });
    }
};
