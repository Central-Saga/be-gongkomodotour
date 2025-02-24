<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripDurationServiceInterface;
use App\Repositories\Contracts\TripDurationRepositoryInterface;

class TripDurationService implements TripDurationServiceInterface
{
    protected $tripDurationRepository;

    const TRIP_DURATION_ALL_CACHE_KEY = 'trip_durations.all';
    const TRIP_DURATION_ACTIVE_CACHE_KEY = 'trip_durations.active';
    const TRIP_DURATION_INACTIVE_CACHE_KEY = 'trip_durations.inactive';

    public function __construct(TripDurationRepositoryInterface $tripDurationRepository)
    {
        $this->tripDurationRepository = $tripDurationRepository;
    }

    public function getAllTripDurations()
    {
        $tripDurations = Cache::remember(self::TRIP_DURATION_ALL_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getAllTripDurations();
        });

        return $tripDurations;
    }

    public function getTripDurationById($id)
    {
        return $this->tripDurationRepository->getTripDurationById($id);
    }

    public function getTripDurationByName($name)
    {
        return $this->tripDurationRepository->getTripDurationByName($name);
    }

    public function getTripDurationByStatus($status)
    {
        return $this->tripDurationRepository->getTripDurationByStatus($status);
    }

    public function getActiveTripDurations()
    {
        $tripDurations = Cache::remember(self::TRIP_DURATION_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getTripDurationByStatus('Aktif');
        });

        return $tripDurations;
    }

    public function getInactiveTripDurations()
    {
        $tripDurations = Cache::remember(self::TRIP_DURATION_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripDurationRepository->getTripDurationByStatus('Non Aktif');
        });

        return $tripDurations;
    }

    public function getTripDurationByTripId($tripId)
    {
        return $this->tripDurationRepository->getTripDurationByTripId($tripId);
    }

    public function createTripDuration(array $data)
    {
        $result = $this->tripDurationRepository->createTripDuration($data);
        $this->clearTripDurationCaches();
        return $result;
    }

    public function updateTripDuration($id, array $data)
    {
        $result = $this->tripDurationRepository->updateTripDuration($id, $data);
        $this->clearTripDurationCaches();
        return $result;
    }

    public function deleteTripDuration($id)
    {
        $result = $this->tripDurationRepository->deleteTripDuration($id);
        $this->clearTripDurationCaches();
        return $result;
    }

    public function updateTripDurationStatus($id, $status)
    {
        $result = $this->tripDurationRepository->updateTripDurationStatus($id, $status);
        $this->clearTripDurationCaches();
        return $result;
    }

    public function clearTripDurationCaches()
    {
        Cache::forget(self::TRIP_DURATION_ALL_CACHE_KEY);
        Cache::forget(self::TRIP_DURATION_ACTIVE_CACHE_KEY);
        Cache::forget(self::TRIP_DURATION_INACTIVE_CACHE_KEY);
    }
}
