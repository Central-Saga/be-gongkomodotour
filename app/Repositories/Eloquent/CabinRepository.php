<?php

namespace App\Repositories\Eloquent;

use App\Models\Cabin;
use App\Repositories\Contracts\CabinRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CabinRepository implements CabinRepositoryInterface
{
    protected $model;

    public function __construct(Cabin $cabin)
    {
        $this->model = $cabin;
    }

    public function getAllCabin()
    {
        return $this->model->all();
    }

    public function getCabinById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function createCabin(array $data)
    {
        return $this->model->create($data);
    }

    public function updateCabin($id, array $data)
    {
        $cabin = $this->findCabin($id);

        if ($cabin) {
            $cabin->update($data);
        }

        return $cabin;
    }

    public function deleteCabin($id)
    {
        $cabin = $this->findCabin($id);

        if ($cabin) {
            $cabin->delete();
        }

        return $cabin;
    }

    public function getCabinByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function getCabinByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Menghapus cabin yang tidak ada dalam array ID yang diberikan
     *
     * @param int $boatId
     * @param array $cabinIds
     * @return void
     */
    public function deleteCabinsNotIn($boatId, array $cabinIds)
    {
        return $this->model
            ->where('boat_id', $boatId)
            ->whereNotIn('id', $cabinIds)
            ->delete();
    }

    /**
     * Mencari cabin berdasarkan kriteria tertentu (helper berdasarkan ID).
     *
     * @param int $id
     * @return Cabin|null
     */
    public function findCabin($id)
    {
        try {
            return Cabin::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Cabin with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate cabin status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateCabinStatus($id, $status)
    {
        $cabin = $this->findCabin($id);

        if ($cabin) {
            $cabin->status = $status;
            $cabin->save();
            return $cabin;
        }
        return null;
    }
}
