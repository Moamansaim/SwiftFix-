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
// audit_logs — FR-27: every admin action leaves a fingerprint
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('action', 100);          // 'approve_workshop', 'suspend_user'
            $table->string('target_type', 50);      // 'workshop', 'user'...
            $table->unsignedBigInteger('target_id');
            $table->text('context')->nullable();    // safe details, NEVER secrets
            $table->timestamps();
            $table->index(['admin_id', 'created_at']);
            $table->index(['target_type', 'target_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
