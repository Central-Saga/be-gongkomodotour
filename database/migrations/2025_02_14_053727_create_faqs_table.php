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
        Schema::create('faq', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->enum('category', ['Umum', 'Pembayaran', 'Pemesanan', 'Pembatalan', 'Lainnya'])->default('Umum');
            $table->integer('display_order')->default(1);
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq');
    }
};
