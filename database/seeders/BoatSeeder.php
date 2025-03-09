<?php

namespace Database\Seeders;

use App\Models\Boat;
use Illuminate\Database\Seeder;

class BoatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        Boat::factory(5)->create();
    }
}
