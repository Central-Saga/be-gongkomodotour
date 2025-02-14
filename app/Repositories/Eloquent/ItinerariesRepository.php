<?php

namespace App\Repositories\Eloquent;

use App\Models\Itineraries;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;
use Illuminate\Support\Facades\Log;

class ItinerariesRepository implements ItinerariesRepositoryInterface
{
    /**
     * @var Itineraries
     */
    protected $itineraries;

    /**
     * Konstruktor ItinerariesRepository.
     *
     * @param Itineraries $itineraries
     */
    public function __construct(Itineraries $itineraries)
    {
        $this->itineraries = $itineraries;
    }

    /**
     * Mengambil semua itineraries.
     *
     * @return mixed
     */
    public function getAllItineraries()
    {
        return $this->itineraries->all();
    }

    /**
     * Mengambil itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getItinerariesById($id)
    {
        try {
            // Mengambil itineraries berdasarkan ID, handle jika tidak ditemukan
            return $this->itineraries->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Itineraries with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil itineraries berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getItinerariesByName($name)
    {
        return $this->itineraries->where('name', $name)->first();
    }

    /**
     * Mengambil itineraries berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getItinerariesByStatus($status)
    {
        return $this->itineraries->where('status', $status)->get();
    }

    /**
     * Membuat itineraries baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createItineraries(array $data)
    {
        try {
            return $this->itineraries->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create itineraries: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui itineraries berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateItineraries($id, array $data)
    {
        $itineraries = $this->findItineraries($id);

        if ($itineraries) {
            try {
                $itineraries->update($data);
                return $itineraries;
            } catch (\Exception $e) {
                Log::error("Failed to update itineraries with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteItineraries($id)
    {
        $itineraries = $this->findItineraries($id);

        if ($itineraries) {
            try {
                $itineraries->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete itineraries with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findItineraries($id)
    {
        try {
            return $this->itineraries->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Itineraries with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus itineraries berdasarkan trip_id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteItinerariesByTripId($trip_id)
    {
        try {
            return $this->itineraries->where('trip_id', $trip_id)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete itineraries with trip_id {$trip_id}: {$e->getMessage()}");
            return false;
        }
    }
}
