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
        Schema::create('trip_boat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->foreignId('boat_id')->constrained('boat')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['trip_id', 'boat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_boat');
    }
};
