<?php

namespace Database\Seeders;

use App\Models\AdditionalFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdditionalFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdditionalFee::factory()->count(10)->create();
    }
}
