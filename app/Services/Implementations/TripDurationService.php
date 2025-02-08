<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripDurationServiceInterface;
use App\Repositories\Contracts\TripDurationRepositoryInterface;


class TripDurationService implements TripDurationServiceInterface
{
    protected $tripDurationRepository;

    const TRIPDURATIONS_ALL_CACHE_KEY = 'tripdurations.all';
    const TRIPDURATIONS_ACTIVE_CACHE_KEY = 'tripdurations.active';
    const TRIPDURATIONS_INACTIVE_CACHE_KEY = 'tripdurations.inactive';

    /**
     * Konstruktor TripDurationService.
     *
     * @param TripDurationRepositoryInterface $tripdurationRepository
     */
    public function __construct(TripDurationRepositoryInterface $tripdurationRepository)
    {
        $this->tripDurationRepository = $tripdurationRepository;
    }

    /**
     * Mengambil semua tripdurations.
     *
     * @return mixed
     */
    public function getAllTripDurations()
    {
        return Cache::remember(self::TRIPDURATIONS_ALL_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getAllTripDurations();
        });
    }

    /**
     * Mengambil tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripDurationById($id)
    {
        return $this->tripDurationRepository->getTripDurationById($id);
    }

    /**
     * Mengambil tripduration berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripDurationByName($name)
    {
        return $this->tripDurationRepository->getTripDurationByName($name);
    }

    /**
     * Mengambil tripduration berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripDurationByStatus($status)
    {
        return $this->tripDurationRepository->getTripDurationByStatus($status);
    }

    /**
     * Mengambil tripdurations dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveTripDurations()
    {
        return Cache::remember(self::TRIPDURATIONS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getTripDurationByStatus('Aktif');
        });
    }

    /**
     * Mengambil tripdurations dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripDurations()
    {
        return Cache::remember(self::TRIPDURATIONS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getTripDurationByStatus('Non Aktif');
        });
    }

    /**
     * Membuat tripduration baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripDuration(array $data)
    {
        $result = $this->tripDurationRepository->createTripDuration($data);
        Cache::forget(self::TRIPDURATIONS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPDURATIONS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui tripduration berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripDuration($id, array $data)
    {
        $result = $this->tripDurationRepository->updateTripDuration($id, $data);
        Cache::forget(self::TRIPDURATIONS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPDURATIONS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus tripduration berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTripDuration($id)
    {
        $result = $this->tripDurationRepository->deleteTripDuration($id);
        Cache::forget(self::TRIPDURATIONS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPDURATIONS_ACTIVE_CACHE_KEY);

        return $result;
    }
}
