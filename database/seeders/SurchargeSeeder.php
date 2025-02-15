<?php

namespace Database\Seeders;

use App\Models\Surcharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurchargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Surcharge::factory()->count(10)->create();
    }
}
