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
        Schema::table('trips', function (Blueprint $table) {
            $table->json('operational_days')->nullable()->after('has_hotel')->comment('Array of days: ["Monday", "Tuesday", etc]');
            $table->enum('tentation', ['Yes', 'No'])->default('No')->after('operational_days')->comment('Whether this trip is a tentation/teaser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['operational_days', 'tentation']);
        });
    }
};
