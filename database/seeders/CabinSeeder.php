<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabin;
use App\Models\Boat;

class CabinSeeder extends Seeder {
    public function run() {
        // Pastikan ada boat sebelum membuat cabin
        $boats = Boat::all();

        if ($boats->isEmpty()) {
            $this->command->info('Tidak ada boat ditemukan! Jalankan BoatSeeder dulu.');
            return;
        }

        // Setiap boat memiliki 3 cabin
        foreach ($boats as $boat) {
            Cabin::factory(3)->create(['boat_id' => $boat->id]);
        }
    }
}

