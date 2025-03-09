<?php

namespace Database\Seeders;

use App\Models\HotelRequest;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HotelRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HotelRequest::factory()->count(10)->create();
    }
}
