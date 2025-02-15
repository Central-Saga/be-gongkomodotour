<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripPricesServiceInterface;
use App\Repositories\Contracts\TripPricesRepositoryInterface;


class TripPricesService implements TripPricesServiceInterface
{
    protected $tripPricesRepository;

    const TRIPPRICESS_ALL_CACHE_KEY = 'trippricess.all';
    const TRIPPRICESS_ACTIVE_CACHE_KEY = 'trippricess.active';
    const TRIPPRICESS_INACTIVE_CACHE_KEY = 'trippricess.inactive';

    /**
     * Konstruktor TripPricesService.
     *
     * @param TripPricesRepositoryInterface $trippricesRepository
     */
    public function __construct(TripPricesRepositoryInterface $trippricesRepository)
    {
        $this->tripPricesRepository = $trippricesRepository;
    }

    /**
     * Mengambil semua trippricess.
     *
     * @return mixed
     */
    public function getAllTripPrices()
    {
        return Cache::remember(self::TRIPPRICESS_ALL_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getAllTripPricess();
        });
    }

    /**
     * Mengambil tripprices berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripPricesById($id)
    {
        return $this->tripPricesRepository->getTripPricesById($id);
    }

    /**
     * Mengambil tripprices berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripPricesByName($name)
    {
        return $this->tripPricesRepository->getTripPricesByName($name);
    }

    /**
     * Mengambil tripprices berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripPricesByStatus($status)
    {
        return $this->tripPricesRepository->getTripPricesByStatus($status);
    }

    /**
     * Mengambil trippricess dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveTripPricess()
    {
        return Cache::remember(self::TRIPPRICESS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getTripPricesByStatus('Aktif');
        });
    }

    /**
     * Mengambil trippricess dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripPricess()
    {
        return Cache::remember(self::TRIPPRICESS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getTripPricesByStatus('Non Aktif');
        });
    }

    /**
     * Membuat tripprices baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripPrices(array $data)
    {
        $result = $this->tripPricesRepository->createTripPrices($data);
        Cache::forget(self::TRIPPRICESS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPPRICESS_ACTIVE_CACHE_KEY);
        return $result;
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
        $result = $this->tripPricesRepository->updateTripPrices($id, $data);
        Cache::forget(self::TRIPPRICESS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPPRICESS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus tripprices berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTripPrices($id)
    {
        $result = $this->tripPricesRepository->deleteTripPrices($id);
        Cache::forget(self::TRIPPRICESS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPPRICESS_ACTIVE_CACHE_KEY);

        return $result;
    }
}
