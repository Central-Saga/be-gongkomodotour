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
        Schema::table('assets', function (Blueprint $table) {
            // Ubah kolom file_url dari string menjadi text untuk menampung URL panjang
            $table->text('file_url')->change();

            // Ubah kolom file_path juga menjadi text untuk konsistensi
            $table->text('file_path')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            // Kembalikan ke string dengan panjang yang cukup
            $table->string('file_url', 1000)->change();
            $table->string('file_path', 1000)->change();
        });
    }
};
