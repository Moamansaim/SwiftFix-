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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('workshop_id')->constrained()->cascadeOnDelete();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->text('issue_description');
            $table->enum('status', [
                'pending', 'accepted', 'rejected', 'received',
                'in_progress', 'ready_for_pickup', 'completed', 'cancelled'
            ])->default('pending')->index();
            // ADR-003: the slot-lock. Exists only while the booking is "active".
            $table->string('active_slot', 32)->nullable()->storedAs(
                "CASE WHEN status IN ('pending','accepted','received','in_progress','ready_for_pickup')
                THEN CONCAT(CAST(appointment_date AS CHAR), ' ', CAST(appointment_time AS CHAR))
                ELSE NULL END"
            );
            $table->unique(['workshop_id', 'active_slot']); // MySQL ignores NULL → freed slots reopen
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
