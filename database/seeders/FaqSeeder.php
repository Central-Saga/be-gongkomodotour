<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faqs = [
            [
                'question' => 'Bagaimana cara memesan paket tour?',
                'answer' => 'Anda dapat memesan paket tour melalui website kami. Pilih paket yang diinginkan, tentukan tanggal keberangkatan, dan lakukan pembayaran sesuai instruksi yang diberikan.',
                'category' => 'Pemesanan',
                'display_order' => 1,
                'status' => 'Aktif'
            ],
            [
                'question' => 'Apa saja metode pembayaran yang tersedia?',
                'answer' => 'Kami menerima pembayaran melalui transfer bank, e-wallet (OVO, DANA, Gopay), dan kartu kredit. Detail pembayaran akan diberikan setelah pemesanan.',
                'category' => 'Pembayaran',
                'display_order' => 2,
                'status' => 'Aktif'
            ],
            [
                'question' => 'Apakah bisa membatalkan pemesanan?',
                'answer' => 'Ya, Anda dapat membatalkan pemesanan dengan ketentuan sebagai berikut: Pembatalan lebih dari 7 hari sebelum keberangkatan dikenakan biaya 10%, 3-7 hari sebelum keberangkatan dikenakan biaya 30%, dan kurang dari 3 hari tidak dapat dibatalkan.',
                'category' => 'Pembatalan',
                'display_order' => 3,
                'status' => 'Aktif'
            ],
            [
                'question' => 'Apa saja yang termasuk dalam paket tour?',
                'answer' => 'Paket tour kami mencakup transportasi, akomodasi, makan sesuai jadwal, tiket masuk objek wisata, dan pemandu wisata. Detail lengkap dapat dilihat di deskripsi masing-masing paket.',
                'category' => 'Umum',
                'display_order' => 4,
                'status' => 'Aktif'
            ],
            [
                'question' => 'Bagaimana jika terjadi perubahan jadwal?',
                'answer' => 'Perubahan jadwal dapat dilakukan minimal 7 hari sebelum keberangkatan, dengan syarat dan ketentuan yang berlaku. Silakan hubungi customer service kami untuk informasi lebih lanjut.',
                'category' => 'Umum',
                'display_order' => 5,
                'status' => 'Aktif'
            ],
            [
                'question' => 'Apakah ada batasan usia untuk mengikuti tour?',
                'answer' => 'Tidak ada batasan usia khusus, namun untuk peserta di bawah 18 tahun wajib didampingi oleh orang tua atau wali yang sah. Beberapa aktivitas mungkin memiliki batasan usia tertentu.',
                'category' => 'Umum',
                'display_order' => 6,
                'status' => 'Aktif'
            ]
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
