<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripPricesServiceInterface;
use App\Repositories\Contracts\TripPricesRepositoryInterface;

class TripPricesService implements TripPricesServiceInterface
{
    protected $tripPricesRepository;

    const TRIP_PRICES_ALL_CACHE_KEY = 'trip_prices.all';
    const TRIP_PRICES_ACTIVE_CACHE_KEY = 'trip_prices.active';
    const TRIP_PRICES_INACTIVE_CACHE_KEY = 'trip_prices.inactive';

    public function __construct(TripPricesRepositoryInterface $tripPricesRepository)
    {
        $this->tripPricesRepository = $tripPricesRepository;
    }

    public function getAllTripPrices()
    {
        $tripPrices = Cache::remember(self::TRIP_PRICES_ALL_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getAllTripPrices();
        });

        return $tripPrices;
    }

    public function getTripPriceById($id)
    {
        return $this->tripPricesRepository->getTripPriceById($id);
    }

    public function getTripPriceByName($name)
    {
        return $this->tripPricesRepository->getTripPriceByName($name);
    }

    public function getTripPriceByStatus($status)
    {
        return $this->tripPricesRepository->getTripPriceByStatus($status);
    }

    public function getActiveTripPrices()
    {
        $tripPrices = Cache::remember(self::TRIP_PRICES_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getTripPriceByStatus('Aktif');
        });

        return $tripPrices;
    }

    public function getInactiveTripPrices()
    {
        $tripPrices = Cache::remember(self::TRIP_PRICES_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->tripPricesRepository->getTripPriceByStatus('Non Aktif');
        });

        return $tripPrices;
    }

    public function getTripPriceByTripId($tripId)
    {
        return $this->tripPricesRepository->getTripPriceByTripId($tripId);
    }

    public function createTripPrice(array $data)
    {
        $result = $this->tripPricesRepository->createTripPrice($data);
        $this->clearTripPricesCaches();
        return $result;
    }

    public function updateTripPrice($id, array $data)
    {
        $result = $this->tripPricesRepository->updateTripPrice($id, $data);
        $this->clearTripPricesCaches();
        return $result;
    }

    public function deleteTripPrice($id)
    {
        $result = $this->tripPricesRepository->deleteTripPrice($id);
        $this->clearTripPricesCaches();
        return $result;
    }

    public function updateTripPriceStatus($id, $status)
    {
        $result = $this->tripPricesRepository->updateTripPriceStatus($id, $status);
        $this->clearTripPricesCaches();
        return $result;
    }

    public function clearTripPricesCaches()
    {
        Cache::forget(self::TRIP_PRICES_ALL_CACHE_KEY);
        Cache::forget(self::TRIP_PRICES_ACTIVE_CACHE_KEY);
        Cache::forget(self::TRIP_PRICES_INACTIVE_CACHE_KEY);
    }
}
