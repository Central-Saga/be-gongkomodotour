<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\Trips;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua trip
        $trips = Trips::all();

        // Buat 10 testimonial untuk setiap trip
        foreach ($trips as $trip) {
            Testimonial::factory()->count(10)->create([
                'trip_id' => $trip->id
            ]);
        }

        // Buat beberapa testimonial internal (tanpa trip_id)
        Testimonial::factory()->count(20)->create([
            'trip_id' => null
        ]);

        // Pastikan ada 10 testimonial yang di-highlight
        $highlightedCount = Testimonial::where('is_highlight', true)->count();
        if ($highlightedCount < 10) {
            // Jika kurang dari 10, tambahkan highlight ke testimonial yang belum di-highlight
            $needed = 10 - $highlightedCount;
            Testimonial::where('is_highlight', false)
                ->inRandomOrder()
                ->limit($needed)
                ->update(['is_highlight' => true]);
        } elseif ($highlightedCount > 10) {
            // Jika lebih dari 10, kurangi highlight ke testimonial yang sudah di-highlight
            $excess = $highlightedCount - 10;
            Testimonial::where('is_highlight', true)
                ->inRandomOrder()
                ->limit($excess)
                ->update(['is_highlight' => false]);
        }
    }
}
