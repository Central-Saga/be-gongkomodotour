<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripServiceInterface;
use App\Repositories\Contracts\TripRepositoryInterface;


class TripService implements TripServiceInterface
{
    protected $tripRepository;

    const TRIPS_ALL_CACHE_KEY = 'trips.all';
    const TRIPS_ACTIVE_CACHE_KEY = 'trips.active';
    const TRIPS_INACTIVE_CACHE_KEY = 'trips.inactive';

    /**
     * Konstruktor TripService.
     *
     * @param TripRepositoryInterface $tripRepository
     */
    public function __construct(TripRepositoryInterface $tripRepository)
    {
        $this->tripRepository = $tripRepository;
    }

    /**
     * Mengambil semua trips.
     *
     * @return mixed
     */
    public function getAllTrips()
    {
        return Cache::remember(self::TRIPS_ALL_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getAllTrips();
        });
    }

    /**
     * Mengambil trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripById($id)
    {
        return $this->tripRepository->getTripById($id);
    }

    /**
     * Mengambil trip berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripByName($name)
    {
        return $this->tripRepository->getTripByName($name);
    }

    /**
     * Mengambil trip berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripByStatus($status)
    {
        return $this->tripRepository->getTripByStatus($status);
    }

    /**
     * Mengambil trips dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveTrips()
    {
        return Cache::remember(self::TRIPS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getTripByStatus('Aktif');
        });
    }

    /**
     * Mengambil trips dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTrips()
    {
        return Cache::remember(self::TRIPS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getTripByStatus('Non Aktif');
        });
    }

    /**
     * Membuat trip baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTrip(array $data)
    {
        $result = $this->tripRepository->createTrip($data);
        Cache::forget(self::TRIPS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui trip berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTrip($id, array $data)
    {
        $result = $this->tripRepository->updateTrip($id, $data);
        Cache::forget(self::TRIPS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus trip berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTrip($id)
    {
        $result = $this->tripRepository->deleteTrip($id);
        Cache::forget(self::TRIPS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPS_ACTIVE_CACHE_KEY);

        return $result;
    }
}
