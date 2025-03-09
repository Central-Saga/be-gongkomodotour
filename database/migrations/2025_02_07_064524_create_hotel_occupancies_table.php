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
        Schema::create('hoteloccupancies', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('hotel_type');
            $table->enum('occupancy', ['Single Occupancy', 'Double Occupancy']);
            $table->decimal('price', 20, 2);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoteloccupancies');
    }
};
