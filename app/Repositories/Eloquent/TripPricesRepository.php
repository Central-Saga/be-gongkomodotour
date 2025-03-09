<?php

namespace App\Repositories\Eloquent;

use App\Models\TripPrices;
use App\Repositories\Contracts\TripPricesRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TripPricesRepository implements TripPricesRepositoryInterface
{
    /**
     * @var TripPrices
     */
    protected $tripprices;

    /**
     * Konstruktor TripPricesRepository.
     *
     * @param TripPricess $tripprices
     */
    public function __construct(TripPrices $tripprices)
    {
        $this->tripprices = $tripprices;
    }

    /**
     * Mengambil semua trippricess.
     *
     * @return mixed
     */
    public function getAllTripPrices()
    {
        return $this->tripprices->all();
    }

    /**
     * Mengambil tripprices berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripPricesById($id)
    {
        try {
            // Mengambil tripprices berdasarkan ID, handle jika tidak ditemukan
            return $this->tripprices->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("TripPrices with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil tripprices berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripPricesByName($name)
    {
        return $this->tripprices->where('name', $name)->first();
    }

    /**
     * Mengambil tripprices berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripPricesByStatus($status)
    {
        return $this->tripprices->where('status', $status)->get();
    }

    /**
     * Membuat tripprices baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripPrices(array $data)
    {
        try {
            return $this->tripprices->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create tripprices: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui tripprices berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripPrices($id, array $data)
    {
        $tripprices = $this->findTripPrices($id);

        if ($tripprices) {
            try {
                $tripprices->update($data);
                return $tripprices;
            } catch (\Exception $e) {
                Log::error("Failed to update tripprices with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus tripprices berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripPrices($id)
    {
        $tripprices = $this->findTripPrices($id);

        if ($tripprices) {
            try {
                $tripprices->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete tripprices with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan tripprices berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findTripPrices($id)
    {
        try {
            return $this->tripprices->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("TripPrices with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus tripprices yang tidak ada di trip duration.
     *
     * @param int $trip_duration_id
     * @return mixed
     */
    public function deleteTripPricesNotIn($trip_duration_id, $existing_id)
    {
        return $this->tripprices->where('trip_duration_id', $trip_duration_id)->whereNotIn('id', $existing_id)->delete();
    }

    /**
     * Mengupdate tripprices status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripPricesStatus($id, $status)
    {
        $tripprices = $this->findTripPrices($id);

        if ($tripprices) {
            $tripprices->status = $status;
            $tripprices->save();
            return $tripprices;
        }
        return null;
    }
}
