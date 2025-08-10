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
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['customer_id']);

            // Hapus kolom customer_id
            $table->dropColumn('customer_id');

            // Tambah kolom baru untuk informasi customer
            $table->string('customer_name')->after('id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone')->nullable()->after('customer_email');
            $table->string('source')->default('internal')->after('customer_phone'); // internal atau google_review
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimonial', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['customer_name', 'customer_email', 'customer_phone', 'source']);

            // Tambah kembali kolom customer_id
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        });
    }
};
