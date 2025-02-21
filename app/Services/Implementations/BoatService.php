<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\BoatServiceInterface;
use App\Repositories\Contracts\BoatRepositoryInterface;


class BoatService implements BoatServiceInterface
{
    protected $boatRepository;

    const BOAT_ALL_CACHE_KEY = 'boat.all';
    const BOAT_ACTIVE_CACHE_KEY = 'boat.active';
    const BOAT_INACTIVE_CACHE_KEY = 'boat.inactive';

    /**
     * Konstruktor Boatervice.
     *
     * @param BoatRepositoryInterface $boatRepository
     */
    public function __construct(BoatRepositoryInterface $boatRepository)
    {
        $this->boatRepository = $boatRepository;
    }

    /**
     * Mengambil semua boat.
     *
     * @return mixed
     */
    public function getAllBoat()
    {
        return Cache::remember(self::BOAT_ALL_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getAllBoat();
        });
    }

    /**
     * Mengambil boat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBoatById($id)
    {
        return $this->boatRepository->getBoatById($id);
    }

    /**
     * Mengambil boat berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBoatByName($name)
    {
        return $this->boatRepository->getBoatByName($name);
    }

    /**
     * Mengambil boat berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBoatByStatus($status)
    {
        return $this->boatRepository->getBoatByStatus($status);
    }

    /**
     * Mengambil boat dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveBoat()
    {
        return Cache::remember(self::BOAT_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getBoatByStatus('Aktif');
        });
    }

    /**
     * Mengambil boat dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveBoat()
    {
        return Cache::remember(self::BOAT_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getBoatByStatus('Non Aktif');
        });
    }

    /**
     * Membuat boat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBoat(array $data)
    {
        $data['guard_name'] = 'web';
        $result = $this->boatRepository->createBoat($data);
        $this->clearBoatCaches();
        return $result;
    }

    /**
     * Memperbarui boat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBoat($id, array $data)
    {
        $data['guard_name'] = 'web';
        $result = $this->boatRepository->updateBoat($id, $data);
        $this->clearBoatCaches();
        return $result;
    }

    /**
     * Mengha   pus boat berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBoat($id)
    {
        $result = $this->boatRepository->deleteBoat($id);
        $this->clearBoatCaches();
        return $result;
    }

    /**
     * Menghapus semua cache boat
     *
     * @return void
     */
    public function clearBoatCaches()
    {
        Cache::forget(self::BOAT_ALL_CACHE_KEY);
        Cache::forget(self::BOAT_ACTIVE_CACHE_KEY);
        Cache::forget(self::BOAT_INACTIVE_CACHE_KEY);
    }
}
