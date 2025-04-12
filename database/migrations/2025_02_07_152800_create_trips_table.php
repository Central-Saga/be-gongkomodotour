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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('include');
            $table->text('exclude');
            $table->text('note');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('meeting_point');
            $table->enum('type', ['Open Trip', 'Private Trip']);
            $table->enum('is_highlight', ['Yes', 'No']);
            $table->enum('status', ['Aktif', 'Non Aktif']);
            $table->integer('destination_count')->default(0);
            $table->boolean('has_boat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
