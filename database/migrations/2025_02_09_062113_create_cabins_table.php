<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cabin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boat_id')->constrained('boat')->onDelete('cascade');
            $table->string('cabin_name');
            $table->string('bed_type');
            $table->integer('min_pax');
            $table->integer('max_pax');
            $table->decimal('base_price', 10, 2);
            $table->decimal('additional_price', 10, 2)->nullable();
            $table->enum('status', allowed: ['available', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabin');
    }
};
