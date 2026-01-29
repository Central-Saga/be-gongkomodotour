<?php

/**
 * Adds price_type (fixed/by_request) and price_per_pax_nullable for "By Request" pricing.
 *
 * FUTURE CLEANUP MIGRATION (do not run yet):
 * After all application code and consumers use price_per_pax_nullable and no code reads
 * price_per_pax anymore:
 * 1. Create a new migration.
 * 2. Copy price_per_pax_nullable into a temporary column if needed, then drop column
 *    price_per_pax, then rename price_per_pax_nullable to price_per_pax (or add a new
 *    migration that drops price_per_pax only).
 * 3. Do NOT use Schema::table()->change() (project does not use doctrine/dbal).
 * 4. Example down-safe approach: add migration that drops column 'price_per_pax' only
 *    after confirming nothing references it.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds price_type (fixed/by_request) and price_per_pax_nullable for "By Request" pricing.
     * Does NOT modify existing price_per_pax column.
     */
    public function up(): void
    {
        Schema::table('trip_prices', function (Blueprint $table) {
            $table->enum('price_type', ['fixed', 'by_request'])->default('fixed')->after('price_per_pax');
            $table->decimal('price_per_pax_nullable', 15, 2)->nullable()->after('price_type');
        });

        // Backfill: copy existing price_per_pax into price_per_pax_nullable
        DB::table('trip_prices')->update([
            'price_per_pax_nullable' => DB::raw('price_per_pax'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trip_prices', function (Blueprint $table) {
            $table->dropColumn(['price_type', 'price_per_pax_nullable']);
        });
    }
};
