<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('Password'),
        ]);

        // Assign the Super Admin role to the user
        $superAdmin->assignRole('Super Admin');
        // Remove the user from the default role
        $superAdmin->removeRole('Pelanggan');

        User::factory(10)->create();
    }
}
