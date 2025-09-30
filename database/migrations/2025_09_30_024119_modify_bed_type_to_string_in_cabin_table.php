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
        Schema::table('cabin', function (Blueprint $table) {
            $table->string('bed_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabin', function (Blueprint $table) {
            $table->enum('bed_type', ['Single', 'Double', 'Queen', 'King'])->change();
        });
    }
};
