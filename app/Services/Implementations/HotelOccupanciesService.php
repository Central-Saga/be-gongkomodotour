<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\HotelOccupanciesServiceInterface;
use App\Repositories\Contracts\HotelOccupanciesRepositoryInterface;


class HotelOccupanciesService implements HotelOccupanciesServiceInterface
{
    protected $hotelOccupanciesRepository;

    const HOTELOCCUPANCIESS_ALL_CACHE_KEY = 'hotelOccupancies.all';
    const HOTELOCCUPANCIESS_ACTIVE_CACHE_KEY = 'hotelOccupancies.active';
    const HOTELOCCUPANCIESS_INACTIVE_CACHE_KEY = 'hotelOccupancies.inactive';

    /**
     * Konstruktor HotelOccupanciesService.
     *
     * @param HotelOccupanciesRepositoryInterface $hotelOccupanciesRepository
     */
    public function __construct(HotelOccupanciesRepositoryInterface $hotelOccupanciesRepository)
    {
        $this->hotelOccupanciesRepository = $hotelOccupanciesRepository;
    }

    /**
     * Mengambil semua hotelOccupanciess.
     *
     * @return mixed
     */
    public function getAllHotelOccupancies()
    {
        return Cache::remember(self::HOTELOCCUPANCIESS_ALL_CACHE_KEY, 3600, function () {
            return $this->hotelOccupanciesRepository->getAllHotelOccupancies();
        });
    }

    /**
     * Mengambil hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelOccupanciesById($id)
    {
        return $this->hotelOccupanciesRepository->getHotelOccupanciesById($id);
    }

    /**
     * Mengambil hotelOccupancies berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelOccupanciesByName($name)
    {
        return $this->hotelOccupanciesRepository->getHotelOccupanciesByName($name);
    }

    /**
     * Mengambil hotelOccupancies berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelOccupanciesByStatus($status)
    {
        return $this->hotelOccupanciesRepository->getHotelOccupanciesByStatus($status);
    }

    /**
     * Mengambil hotelOccupanciess dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveHotelOccupancies()
    {
        return Cache::remember(self::HOTELOCCUPANCIESS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->hotelOccupanciesRepository->getHotelOccupanciesByStatus('Aktif');
        });
    }

    /**
     * Mengambil hotelOccupanciess dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveHotelOccupancies()
    {
        return Cache::remember(self::HOTELOCCUPANCIESS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->hotelOccupanciesRepository->getHotelOccupanciesByStatus('Non Aktif');
        });
    }

    /**
     * Membuat hotelOccupancies baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelOccupancies(array $data)
    {
        Log::info('Creating HotelOccupancies with data: ' . json_encode($data));
        $result = $this->hotelOccupanciesRepository->createHotelOccupancies($data);
        Cache::forget(self::HOTELOCCUPANCIESS_ALL_CACHE_KEY);
        Cache::forget(self::HOTELOCCUPANCIESS_ACTIVE_CACHE_KEY);
        return $result;
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
        $result = $this->hotelOccupanciesRepository->updateHotelOccupancies($id, $data);
        Cache::forget(self::HOTELOCCUPANCIESS_ALL_CACHE_KEY);
        Cache::forget(self::HOTELOCCUPANCIESS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteHotelOccupancies($id)
    {
        $result = $this->hotelOccupanciesRepository->deleteHotelOccupancies($id);
        Cache::forget(self::HOTELOCCUPANCIESS_ALL_CACHE_KEY);
        Cache::forget(self::HOTELOCCUPANCIESS_ACTIVE_CACHE_KEY);

        return $result;
    }
}
