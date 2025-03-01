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
        Schema::create('additional_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained('trips')->onDelete('cascade');
            $table->string('fee_category');
            $table->decimal('price', 15, 2);
            $table->enum('region', ['Domestic', 'Overseas', 'Domestic & Overseas']);
            $table->enum('unit', ['per_pax', 'per_5pax', 'per_day', 'per_day_guide']);
            $table->integer('pax_min');
            $table->integer('pax_max');
            $table->enum('day_type', ['Weekday', 'Weekend'])->nullable();
            $table->boolean('is_required')->default(true);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_fees');
    }
};
