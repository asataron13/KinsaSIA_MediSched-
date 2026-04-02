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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name');
            $table->string('system_name', 100)->default('MediSched');
            $table->string('contact_number', 20);
            $table->string('email');
            $table->text('address');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(true);
            $table->boolean('new_booking_alerts')->default(false);
            $table->boolean('cancellation_alerts')->default(true);
            $table->integer('max_bookings_per_day');
            $table->integer('booking_window_days');
            $table->integer('consultation_duration_mins');
            $table->integer('cancellation_policy_hours');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
