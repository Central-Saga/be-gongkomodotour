<?php

namespace App\Repositories\Eloquent;

use App\Models\Boat;
use App\Repositories\Contracts\BoatRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BoatRepository implements BoatRepositoryInterface
{
    public function getAllBoat()
    {
        return Boat::all();
    }

    public function getBoatById($id)
    {
        return Boat::findOrFail($id);
    }

    public function createBoat(array $data)
    {
        return Boat::create($data);
    }

    public function updateBoat($id, array $data)
    {
        $Boat = $this->findBoat($id);

        if ($Boat) {
            $Boat->update($data);
        }

        return $Boat;
    }

    public function deleteBoat($id)
    {
        $Boat = $this->findBoat($id);

        if ($Boat) {
            $Boat->delete();
        }

        return $Boat;
    }

    public function getBoatByName($name)
    {
        return Boat::where('name', $name)->first();
    }

    public function getBoatByStatus($status)
    {
        return Boat::where('status', $status)->get();
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
             return Boat::findOrFail($id);
         } catch (ModelNotFoundException $e) {
             Log::error("Boat with ID {$id} not found.");
             return null;
         }
     }
}