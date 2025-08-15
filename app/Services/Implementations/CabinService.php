<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\CabinServiceInterface;
use App\Repositories\Contracts\CabinRepositoryInterface;


class CabinService implements CabinServiceInterface
{
    protected $cabinRepository;

    const CABIN_ALL_CACHE_KEY = 'cabin.all';
    const CABIN_ACTIVE_CACHE_KEY = 'cabin.active';
    const CABIN_INACTIVE_CACHE_KEY = 'cabin.inactive';

    /**
     * Konstruktor Cabinervice.
     *
     * @param CabinRepositoryInterface $cabinRepository
     */
    public function __construct(CabinRepositoryInterface $cabinRepository)
    {
        $this->cabinRepository = $cabinRepository;
    }

    /**
     * Mengambil semua cabin.
     *
     * @return mixed
     */
    public function getAllCabin()
    {
        return Cache::remember(self::CABIN_ALL_CACHE_KEY, 3600, function () {
            return $this->cabinRepository->getAllCabin();
        });
    }

    /**
     * Mengambil cabin berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCabinById($id)
    {
        return $this->cabinRepository->getCabinById($id);
    }

    /**
     * Mengambil cabin berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getCabinByName($name)
    {
        return $this->cabinRepository->getCabinByName($name);
    }

    /**
     * Mengambil cabin berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getCabinByStatus($status)
    {
        return $this->cabinRepository->getCabinByStatus($status);
    }

    /**
     * Mengambil cabin dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveCabin()
    {
        return Cache::remember(self::CABIN_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->cabinRepository->getCabinByStatus('Aktif');
        });
    }

    /**
     * Mengambil cabin dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveCabin()
    {
        return Cache::remember(self::CABIN_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->cabinRepository->getCabinByStatus('Non Aktif');
        });
    }

    /**
     * Membuat cabin baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCabin(array $data)
    {
        $result = $this->cabinRepository->createCabin($data);
        $this->clearCabinCaches();
        return $result;
    }

    /**
     * Memperbarui cabin berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCabin($id, array $data)
    {
        // Ambil cabin existing untuk memastikan boat_id tidak hilang
        $existingCabin = $this->getCabinById($id);

        if (!$existingCabin) {
            return null;
        }

        // Jika boat_id tidak dikirim, gunakan yang existing
        if (!isset($data['boat_id'])) {
            $data['boat_id'] = $existingCabin->boat_id;
        }

        $result = $this->cabinRepository->updateCabin($id, $data);
        $this->clearCabinCaches();
        return $result;
    }

    /**
     * Menghapus cabin berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCabin($id)
    {
        $result = $this->cabinRepository->deleteCabin($id);
        $this->clearCabinCaches();

        return $result;
    }

    public function updateCabinStatus($id, $status)
    {
        $cabin = $this->getCabinById($id);

        if ($cabin) {
            $result = $this->cabinRepository->updateCabinStatus($id, $status);

            $this->clearCabinCaches($id);

            return $result;
        }

        return null;
    }

    /**
     * Menghapus semua cache cabin
     *
     * @return void
     */
    public function clearCabinCaches()
    {
        Cache::forget(self::CABIN_ALL_CACHE_KEY);
        Cache::forget(self::CABIN_ACTIVE_CACHE_KEY);
        Cache::forget(self::CABIN_INACTIVE_CACHE_KEY);
    }
}
