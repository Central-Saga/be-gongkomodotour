<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User
     */
    protected $user;

    /**
     * Konstruktor UserRepository.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Mengambil semua users.
     *
     * @return mixed
     */
    public function getAllUsers()
    {
        return $this->user->all();
    }

    /**
     * Mengambil user berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getUserById($id)
    {
        try {
            // Mengambil user berdasarkan ID, handle jika tidak ditemukan
            return $this->user->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("User with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil user berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getUserByName($name)
    {
        return $this->user->where('name', $name)->first();
    }

    /**
     * Mengambil user berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getUserByStatus($status)
    {
        return $this->user->where('status', $status)->get();
    }

    /**
     * Membuat user baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createUser(array $data)
    {
        try {
            return $this->user->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create user: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui user berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateUser($id, array $data)
    {
        $user = $this->findUser($id);

        if ($user) {
            try {
                $user->update($data);
                return $user;
            } catch (\Exception $e) {
                Log::error("Failed to update user with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus user berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteUser($id)
    {
        $user = $this->findUser($id);

        if ($user) {
            try {
                $user->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete user with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan user berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findUser($id)
    {
        try {
            return $this->user->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("User with ID {$id} not found.");
            return null;
        }
    }
}
