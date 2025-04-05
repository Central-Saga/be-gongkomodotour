<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_blast_recipient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_blast_id')->constrained('email_blast')->onDelete('cascade');
            $table->string('recipient_email');
            $table->enum('status', ['Aktif', 'Non Aktif'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_blast_recipient');
    }
};
