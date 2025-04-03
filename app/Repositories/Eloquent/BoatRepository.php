<?php

namespace App\Repositories\Eloquent;

use App\Models\Boat;
use App\Repositories\Contracts\BoatRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BoatRepository implements BoatRepositoryInterface
{
    protected $model;

    public function __construct(Boat $boat)
    {
        $this->model = $boat;
    }

    public function getAllBoat()
    {
        return $this->model->with(['cabin', 'assets', 'cabin.assets'])->get();
    }

    public function getBoatById($id)
    {
        return $this->model->with(['cabin', 'assets', 'cabin.assets'])->findOrFail($id);
    }

    public function createBoat(array $data)
    {
        $model = $this->model->create($data);
        $model->load(['cabin', 'assets', 'cabin.assets']);
        return $model;
    }

    public function updateBoat($id, array $data)
    {
        $boat = $this->findBoat($id);

        if ($boat) {
            $boat->update($data);
            $boat->load(['cabin', 'assets', 'cabin.assets']);
        }

        return $boat;
    }

    public function deleteBoat($id)
    {
        $boat = $this->findBoat($id);

        if ($boat) {
            $boat->delete();
        }

        return $boat;
    }

    public function getBoatByName($name)
    {
        return $this->model->with(['cabin', 'assets', 'cabin.assets'])->where('name', $name)->first();
    }

    public function getBoatByStatus($status)
    {
        return $this->model->with(['cabin', 'assets', 'cabin.assets'])->where('status', $status)->get();
    }

    /**
     * Mencari Boat berdasarkan kriteria tertentu (helper berdasarkan ID).
     *
     * @param int $id
     * @return Boat|null
     */
    public function findBoat($id)
    {
        try {
            return $this->model->with(['cabin', 'assets', 'cabin.assets'])->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Boat with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate boat status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateBoatStatus($id, $status)
    {
        $boat = $this->findBoat($id);

        if ($boat) {
            $boat->status = $status;
            $boat->save();
            $boat->load(['cabin', 'assets', 'cabin.assets']);
            return $boat;
        }
        return null;
    }
}
