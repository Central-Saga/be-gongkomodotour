<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailBlast;

class EmailBlastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 EmailBlast records
        EmailBlast::factory(10)->create();
    }
}