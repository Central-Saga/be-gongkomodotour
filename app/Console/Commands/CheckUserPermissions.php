<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:check-permissions {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user permissions and roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$email) {
            $email = $this->ask('Masukkan email user yang ingin dicek:');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User dengan email {$email} tidak ditemukan!");
            return 1;
        }

        $this->info("=== INFO USER ===");
        $this->info("ID: {$user->id}");
        $this->info("Name: {$user->name}");
        $this->info("Email: {$email}");
        $this->info("Status: {$user->status}");
        $this->info("");

        // Cek roles
        $this->info("=== ROLES ===");
        $roles = $user->getRoleNames();
        if ($roles->count() > 0) {
            foreach ($roles as $role) {
                $this->info("✓ {$role}");
            }
        } else {
            $this->warn("Tidak ada role!");
        }
        $this->info("");

        // Cek permissions
        $this->info("=== PERMISSIONS ===");
        $permissions = $user->getAllPermissions();
        if ($permissions->count() > 0) {
            foreach ($permissions as $permission) {
                $this->info("✓ {$permission->name}");
            }
        } else {
            $this->warn("Tidak ada permission!");
        }
        $this->info("");

        // Cek permission blogs secara spesifik
        $this->info("=== PERMISSION BLOGS ===");
        $hasBlogPermission = $user->hasPermissionTo('mengelola blogs');
        if ($hasBlogPermission) {
            $this->info("✓ User memiliki permission 'mengelola blogs'");
        } else {
            $this->warn("✗ User TIDAK memiliki permission 'mengelola blogs'");
            $this->info("");
            $this->info("Untuk memberikan permission, jalankan:");
            $this->info("php artisan user:give-permission {$user->id} 'mengelola blogs'");
        }

        return 0;
    }
}
