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
        Schema::create('trip_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_duration_id')->constrained('trip_durations')->onDelete('cascade');
            $table->integer('pax_min');
            $table->integer('pax_max');
            $table->decimal('price_per_pax', 15, 2);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->enum('region', ['Domestic', 'Overseas', 'Domestic & Overseas']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trip_prices');
    }
};
