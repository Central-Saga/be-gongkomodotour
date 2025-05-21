<?php

namespace App\Repositories\Eloquent;

use App\Models\HotelOccupancies;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\HotelOccupanciesRepositoryInterface;


class HotelOccupanciesRepository implements HotelOccupanciesRepositoryInterface
{
    /**
     * @var HotelOccupancies
     */
    protected $hotelOccupancies;

    /**
     * Konstruktor HotelOccupanciesRepository.
     *
     * @param HotelOccupancies $hotelOccupancies
     */
    public function __construct(HotelOccupancies $hotelOccupancies)
    {
        $this->hotelOccupancies = $hotelOccupancies;
    }

    /**
     * Mengambil semua hotelOccupanciess.
     *
     * @return mixed
     */
    public function getAllHotelOccupancies()
    {
        return $this->hotelOccupancies->all();
    }

    /**
     * Mengambil hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelOccupanciesById($id)
    {
        try {
            // Mengambil hotelOccupancies berdasarkan ID, handle jika tidak ditemukan
            return $this->hotelOccupancies->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("HotelOccupancies with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil hotelOccupancies berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelOccupanciesByName($name)
    {
        return $this->hotelOccupancies->where('name', $name)->first();
    }

    /**
     * Mengambil hotelOccupancies berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelOccupanciesByStatus($status)
    {
        return $this->hotelOccupancies->where('status', $status)->get();
    }

    /**
     * Membuat hotelOccupancies baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelOccupancies(array $data)
    {
        try {
            return $this->hotelOccupancies->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create hotelOccupancies: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateHotelOccupancies($id, array $data)
    {
        $hotelOccupancies = $this->findHotelOccupancies($id);

        if ($hotelOccupancies) {
            try {
                $hotelOccupancies->update($data);
                return $hotelOccupancies;
            } catch (\Exception $e) {
                Log::error("Failed to update hotelOccupancies with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteHotelOccupancies($id)
    {
        $hotelOccupancies = $this->findHotelOccupancies($id);

        if ($hotelOccupancies) {
            try {
                $hotelOccupancies->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete hotelOccupancies with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findHotelOccupancies($id)
    {
        try {
            return $this->hotelOccupancies->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("HotelOccupancies with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate hotelOccupancies status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelOccupanciesStatus($id, $status)
    {
        $hotelOccupancies = $this->findHotelOccupancies($id);

        if ($hotelOccupancies) {
            $hotelOccupancies->status = $status;
            $hotelOccupancies->save();
            return $hotelOccupancies;
        }
        return null;
    }

    public function addSurcharge($hotelOccupancyId, array $surchargeData)
    {
        $hotelOccupancy = $this->findHotelOccupancies($hotelOccupancyId);
        if ($hotelOccupancy) {
            return $hotelOccupancy->surcharges()->create($surchargeData);
        }
        return null;
    }
}
