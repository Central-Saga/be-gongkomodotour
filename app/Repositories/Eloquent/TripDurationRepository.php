<?php

namespace App\Repositories\Eloquent;

use App\Models\TripDuration;
use App\Repositories\Contracts\TripDurationRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TripDurationRepository implements TripDurationRepositoryInterface
{
    /**
     * @var TripDuration
     */
    protected $tripduration;

    /**
     * Konstruktor TripDurationRepository.
     *
     * @param TripDuration $tripduration
     */
    public function __construct(TripDuration $tripduration)
    {
        $this->tripduration = $tripduration;
    }

    /**
     * Mengambil semua tripdurations.
     *
     * @return mixed
     */
    public function getAllTripDurations()
    {
        return $this->tripduration->all();
    }

    /**
     * Mengambil tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripDurationById($id)
    {
        try {
            // Mengambil tripduration berdasarkan ID, handle jika tidak ditemukan
            return $this->tripduration->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("TripDuration with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil tripduration berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripDurationByName($name)
    {
        return $this->tripduration->where('name', $name)->first();
    }

    /**
     * Mengambil tripduration berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripDurationByStatus($status)
    {
        return $this->tripduration->where('status', $status)->get();
    }

    /**
     * Membuat tripduration baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripDuration(array $data)
    {
        try {
            return $this->tripduration->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create tripduration: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui tripduration berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripDuration($id, array $data)
    {
        $tripduration = $this->findTripDuration($id);

        if ($tripduration) {
            try {
                $tripduration->update($data);
                return $tripduration;
            } catch (\Exception $e) {
                Log::error("Failed to update tripduration with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripDuration($id)
    {
        $tripduration = $this->findTripDuration($id);

        if ($tripduration) {
            try {
                $tripduration->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete tripduration with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findTripDuration($id)
    {
        try {
            return $this->tripduration->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("TripDuration with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus tripduration berdasarkan trip_id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteTripDurationByTripId($trip_id)
    {
        try {
            return $this->tripduration->where('trip_id', $trip_id)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete tripduration with trip_id {$trip_id}: {$e->getMessage()}");
            return false;
        }
    }
}
