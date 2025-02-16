<?php

namespace App\Repositories\Eloquent;

use App\Models\AdditionalFee;
use App\Repositories\Contracts\AdditionalFeeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class AdditionalFeeRepository implements AdditionalFeeRepositoryInterface
{
    /**
     * @var AdditionalFee
     */
    protected $additionalFee;

    /**
     * Konstruktor TripRepository.
     *
     * @param AdditionalFee $additionalFee
     */
    public function __construct(AdditionalFee $additionalFee)
    {
        $this->additionalFee = $additionalFee;
    }

    /**
     * Mengambil semua additional fees.
     *
     * @return mixed
     */
    public function getAllAdditionalFees()
    {
        return $this->additionalFee->get();
    }

    /**
     * Mengambil trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getAdditionalFeeById($id)
    {
        try {
            // Mengambil trip berdasarkan ID, handle jika tidak ditemukan
            return $this->additionalFee->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Additional Fee with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil additional fee berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getAdditionalFeeByName($name)
    {
        return $this->additionalFee->where('name', $name)->first();
    }

    /**
     * Mengambil additional fee berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAdditionalFeeByStatus($status)
    {
        return $this->additionalFee->where('status', $status)->get();
    }

    /**
     * Mengambil additional fees berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getAdditionalFeesByTripId($trip_id)
    {
        return $this->additionalFee->where('trip_id', $trip_id)->get();
    }

    /**
     * Membuat additional fee baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createAdditionalFee(array $data)
    {
        try {
            $additionalFee = $this->additionalFee->create($data);
            Cache::forget("additional_fee_trip_{$additionalFee->trip_id}");
            return $additionalFee;
        } catch (\Exception $e) {
            Log::error("Failed to create additional fee: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui additional fee berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAdditionalFee($id, array $data)
    {
        $additionalFee = $this->findAdditionalFee($id);

        if ($additionalFee) {
            try {
                $additionalFee->update($data);
                Cache::forget("additional_fee_trip_{$additionalFee->trip_id}");
                return $additionalFee;
            } catch (\Exception $e) {
                Log::error("Failed to update additional fee with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus additional fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteAdditionalFee($id)
    {
        $additionalFee = $this->findAdditionalFee($id);

        if ($additionalFee) {
            try {
                $trip_id = $additionalFee->trip_id;
                $additionalFee->delete();
                Cache::forget("additional_fee_trip_{$trip_id}");
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete additional fee with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan additional fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findAdditionalFee($id)
    {
        try {
            return $this->additionalFee->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Additional Fee with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus additional fees yang tidak terdapat dalam trip.
     *
     * @param int $trip_id
     * @param array $existing_id
     * @return mixed
     */
    public function deleteAdditionalFeesNotIn($trip_id, $existing_id)
    {
        try {
            return $this->additionalFee->where('trip_id', $trip_id)->whereNotIn('id', $existing_id)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete additional fees with trip_id {$trip_id}: {$e->getMessage()}");
            return false;
        }
    }
}
