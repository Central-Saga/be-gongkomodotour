<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('boat', function (Blueprint $table) {
            $table->id();
            $table->string('boat_name');
            $table->text('spesification')->nullable();
            $table->text('cabin_information')->nullable();
            $table->text('facilities')->nullable();
            $table->enum('status', ['Aktif','Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boats');
    }
};
