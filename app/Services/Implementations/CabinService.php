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
        $data['guard_name'] = 'web';
        $result = $this->cabinRepository->createCabin($data);
        Cache::forget(self::CABIN_ALL_CACHE_KEY);
        Cache::forget(self::CABIN_ACTIVE_CACHE_KEY);
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
        $data['guard_name'] = 'web';
        $result = $this->cabinRepository->updateCabin($id, $data);
        Cache::forget(self::CABIN_ALL_CACHE_KEY);
        Cache::forget(self::CABIN_ACTIVE_CACHE_KEY);
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
        Cache::forget(self::CABIN_ALL_CACHE_KEY);
        Cache::forget(self::CABIN_ACTIVE_CACHE_KEY);

        return $result;
    }
}
