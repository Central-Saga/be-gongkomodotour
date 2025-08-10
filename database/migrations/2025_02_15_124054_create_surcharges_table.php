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
        Schema::create('surcharges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_occupancy_id')->constrained('hoteloccupancies')->onDelete('cascade');
            $table->string('season');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('surcharge_price', 15, 2);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surcharges');
    }
};
