<?php

namespace Database\Seeders;

use App\Models\BookingFee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BookingFee::factory()->count(10)->create();
    }
}
