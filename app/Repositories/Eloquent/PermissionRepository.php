<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Facades\Cache;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * Konstruktor PermissionRepository.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Mengambil semua permissions.
     *
     * @return mixed
     */
    public function getAllPermissions()
    {
        return $this->permission->all();
    }

    /**
     * Mengambil permission berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getPermissionById($id)
    {
        try {
            // Mengambil permission berdasarkan ID, handle jika tidak ditemukan
            return $this->permission->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Permission with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil permission berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getPermissionByName($name)
    {
        return $this->permission->where('name', $name)->first();
    }

    /**
     * Mengambil permission berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getPermissionByStatus($status)
    {
        return $this->permission->where('status', $status)->get();
    }

    /**
     * Membuat permission baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createPermission(array $data)
    {
        try {
            return $this->permission->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create permission: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui permission berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updatePermission($id, array $data)
    {
        $permission = $this->findPermission($id);

        if ($permission) {
            try {
                $permission->update($data);
                return $permission;
            } catch (\Exception $e) {
                Log::error("Failed to update permission with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus permission berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deletePermission($id)
    {
        $permission = $this->findPermission($id);

        if ($permission) {
            try {
                $permission->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete permission with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan permission berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findPermission($id)
    {
        try {
            return $this->permission->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Permission with ID {$id} not found.");
            return null;
        }
    }
}
