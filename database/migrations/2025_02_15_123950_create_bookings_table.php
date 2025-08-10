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
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('trip_duration_id')->constrained('trip_durations')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('hotel_occupancy_id')->nullable()->constrained('hoteloccupancies')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_address')->nullable();
            $table->string('customer_country')->nullable();
            $table->string('customer_phone')->nullable();
            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('total_pax');
            $table->date('start_date');
            $table->boolean('is_hotel_requested')->default(false);
            $table->date('end_date');
            $table->enum('status', ['Pending', 'Confirmed', 'Cancelled'])->default('Pending');
            $table->timestamps();
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
