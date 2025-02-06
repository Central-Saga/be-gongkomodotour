<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a super admin user using the User factory
        $superAdmin = User::factory()->create([
            'name'  => 'Super Admin',
            'email' => 'superadmin@example.com',
        ]);

        // Assign the Super Admin role
        $superAdmin->assignRole('Super Admin');
        // Remove the user from the default role
        $superAdmin->removeRole('Pelanggan');

        // Create additional users as needed using the factory
        // Example: create an admin user
        $admin = User::factory()->create([
            'name'  => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole('Admin');
        // Remove the user from the default role
        $admin->removeRole('Pelanggan');

        // Example: create a pelanggan user
        $pelanggan = User::factory()->create([
            'name'  => 'Pelanggan User',
            'email' => 'pelanggan@example.com',
        ]);
        $pelanggan->assignRole('Pelanggan');



        User::factory(10)->create();
    }
}
