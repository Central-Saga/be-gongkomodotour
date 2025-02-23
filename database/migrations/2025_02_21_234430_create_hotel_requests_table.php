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
        Schema::create('hotel_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions');
            $table->foreignId('user_id')->constrained('users');
            $table->string('confirmed_note');
            $table->enum('requested_hotel_name', ['Ayana Komodo Resort', 'Meruorah Hotel']);
            $table->enum('request_status', ['Menunggu Konfirmasi', 'Diterima', 'Ditolak'])->default('Menunggu Konfirmasi');
            $table->decimal('confirmed_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_requests');
    }
};
