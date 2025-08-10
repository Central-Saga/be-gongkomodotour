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
            $table->enum('bed_type', ['Single', 'Double', 'Queen', 'King']);
            $table->integer('min_pax');
            $table->integer('max_pax');
            $table->decimal('base_price', 10, 2);
            $table->decimal('additional_price', 10, 2)->nullable();
            $table->enum('status', allowed: ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabin');
    }
};
