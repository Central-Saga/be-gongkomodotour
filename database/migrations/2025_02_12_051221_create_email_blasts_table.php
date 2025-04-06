<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_blast', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->enum('recipient_type', ['all_customers', 'subscribers', 'spesific_list']);
            $table->enum('status', ['Draft', 'Scheduled', 'Processing', 'Sent', 'Failed'])->default('Draft');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_blast');
    }
};
