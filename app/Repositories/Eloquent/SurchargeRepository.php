<?php

namespace App\Repositories\Eloquent;

use App\Models\Surcharge;
use App\Repositories\Contracts\SurchargeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SurchargeRepository implements SurchargeRepositoryInterface
{
    /**
     * @var Surcharge
     */
    protected $surcharge;

    /**
     * Konstruktor SurchargeRepository.
     *
     * @param Surcharge $surcharge
     */
    public function __construct(Surcharge $surcharge)
    {
        $this->surcharge = $surcharge;
    }

    /**
     * Mengambil semua surcharges.
     *
     * @return mixed
     */
    public function getAllSurcharges()
    {
        return $this->surcharge->get();
    }

    /**
     * Mengambil surcharge berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSurchargeById($id)
    {
        try {
            // Mengambil trip berdasarkan ID, handle jika tidak ditemukan
            return $this->surcharge->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Surcharge with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil surcharge berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSurchargeByName($name)
    {
        return $this->surcharge->where('name', $name)->first();
    }

    /**
     * Mengambil surcharge berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getSurchargeByStatus($status)
    {
        return $this->surcharge->where('status', $status)->get();
    }

    /**
     * Membuat surcharge baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSurcharge(array $data)
    {
        try {
            return $this->surcharge->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create surcharge: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui surcharge berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSurcharge($id, array $data)
    {
        $surcharge = $this->findSurcharge($id);

        if ($surcharge) {
            try {
                $surcharge->update($data);
                return $surcharge;
            } catch (\Exception $e) {
                Log::error("Failed to update surcharge with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus surcharge berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSurcharge($id)
    {
        $surcharge = $this->findSurcharge($id);

        if ($surcharge) {
            try {
                $surcharge->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete surcharge with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan surcharge berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findSurcharge($id)
    {
        try {
            return $this->surcharge->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Surcharge with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus surcharges yang tidak terdapat dalam trip.
     *
     * @param int $trip_id
     * @param array $existing_id
     * @return mixed
     */
    public function deleteSurchargesNotIn($trip_id, $existing_id)
    {
        try {
            return $this->surcharge->where('trip_id', $trip_id)->whereNotIn('id', $existing_id)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete surcharges with trip_id {$trip_id}: {$e->getMessage()}");
            return false;
        }
    }
}
