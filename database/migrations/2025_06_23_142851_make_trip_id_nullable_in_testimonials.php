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
        Schema::table('testimonial', function (Blueprint $table) {
            // Hapus foreign key constraint untuk trip_id
            $table->dropForeign(['trip_id']);

            // Buat trip_id nullable
            $table->foreignId('trip_id')->nullable()->change();

            // Tambahkan kembali foreign key dengan onDelete set null
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('set null');

            // Hapus kolom google_review_id karena tidak diperlukan
            $table->dropColumn('google_review_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial', function (Blueprint $table) {
            // Hapus foreign key constraint
            $table->dropForeign(['trip_id']);

            // Buat trip_id tidak nullable lagi
            $table->foreignId('trip_id')->nullable(false)->change();

            // Tambahkan kembali foreign key dengan onDelete cascade
            $table->foreign('trip_id')->references('id')->on('trips')->onDelete('cascade');

            // Tambahkan kembali kolom google_review_id
            $table->string('google_review_id')->nullable()->after('source');
        });
    }
};
