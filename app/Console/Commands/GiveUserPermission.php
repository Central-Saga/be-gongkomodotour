<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class GiveUserPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:give-permission {userId} {permission}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give permission to user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userId');
        $permissionName = $this->argument('permission');

        $user = User::find($userId);

        if (!$user) {
            $this->error("User dengan ID {$userId} tidak ditemukan!");
            return 1;
        }

        // Cek apakah permission sudah ada
        $permission = Permission::where('name', $permissionName)->first();

        if (!$permission) {
            $this->error("Permission '{$permissionName}' tidak ditemukan!");
            $this->info("");
            $this->info("Permission yang tersedia:");
            $allPermissions = Permission::all();
            foreach ($allPermissions as $perm) {
                $this->info("- {$perm->name}");
            }
            return 1;
        }

        // Cek apakah user sudah punya permission
        if ($user->hasPermissionTo($permissionName)) {
            $this->warn("User sudah memiliki permission '{$permissionName}'");
            return 0;
        }

        // Berikan permission
        $user->givePermissionTo($permissionName);

        $this->info("✓ Permission '{$permissionName}' berhasil diberikan ke user {$user->name} ({$user->email})");

        // Refresh dan cek lagi
        $user->refresh();
        if ($user->hasPermissionTo($permissionName)) {
            $this->info("✓ Verifikasi: User sekarang memiliki permission '{$permissionName}'");
        } else {
            $this->error("✗ Verifikasi gagal: User tidak memiliki permission '{$permissionName}'");
        }

        return 0;
    }
}
