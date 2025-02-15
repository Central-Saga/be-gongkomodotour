<?php

namespace App\Repositories\Eloquent;

use App\Models\Cabin;
use App\Repositories\Contracts\CabinRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CabinRepository implements CabinRepositoryInterface
{
    public function getAllCabin()
    {
        return Cabin::all();
    }

    public function getCabinById($id)
    {
        return Cabin::findOrFail($id);
    }

    public function createCabin(array $data)
    {
        return Cabin::create($data);
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
        return Cabin::where('name', $name)->first();
    }

    public function getCabinByStatus($status)
    {
        return Cabin::where('status', $status)->get();
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
}