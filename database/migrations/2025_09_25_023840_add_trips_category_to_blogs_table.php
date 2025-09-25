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
        Schema::table('blog', function (Blueprint $table) {
            // Mengubah enum category untuk menambahkan 'trips'
            $table->enum('category', ['travel', 'tips', 'trips'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog', function (Blueprint $table) {
            // Mengembalikan enum category ke kondisi semula
            $table->enum('category', ['travel', 'tips'])->change();
        });
    }
};
