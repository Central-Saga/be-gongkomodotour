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
        Schema::create('hotel_occupancies', function (Blueprint $table) {
            $table->id();
            $table->string('hotel_name');
            $table->string('hotel_type');
            $table->integer('occupancy');
            $table->decimal('price', 8, 2);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_occupancies');
    }
};
