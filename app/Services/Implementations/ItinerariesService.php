<?php

namespace App\Services\Implementations;

use App\Services\Contracts\ItinerariesServiceInterface;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ItinerariesService implements ItinerariesServiceInterface
{
    protected $itinerariesRepository;

    const ITINERARIES_ALL_CACHE_KEY = 'itineraries.all';

    public function __construct(ItinerariesRepositoryInterface $itinerariesRepository)
    {
        $this->itinerariesRepository = $itinerariesRepository;
    }

    public function getAllItineraries()
    {
        $itineraries = Cache::remember(self::ITINERARIES_ALL_CACHE_KEY, 3600, function () {
            return $this->itinerariesRepository->getAllItineraries();
        });

        return $itineraries;
    }

    public function getItineraryById($id)
    {
        return $this->itinerariesRepository->getItineraryById($id);
    }

    public function getItineraryByStatus($status)
    {
        return $this->itinerariesRepository->getItineraryByStatus($status);
    }

    public function getItineraryByName($name)
    {
        return $this->itinerariesRepository->getItineraryByName($name);
    }

    public function getItineraryByTripId($tripId)
    {
        return $this->itinerariesRepository->getItineraryByTripId($tripId);
    }

    public function createItinerary(array $data)
    {
        $result = $this->itinerariesRepository->createItinerary($data);
        $this->clearItinerariesCache();
        return $result;
    }

    public function updateItinerary($id, array $data)
    {
        $result = $this->itinerariesRepository->updateItinerary($id, $data);
        $this->clearItinerariesCache();
        return $result;
    }

    public function deleteItinerary($id)
    {
        $result = $this->itinerariesRepository->deleteItinerary($id);
        $this->clearItinerariesCache();
        return $result;
    }

    public function updateItineraryStatus($id, $status)
    {
        $result = $this->itinerariesRepository->updateItineraryStatus($id, $status);
        $this->clearItinerariesCache();
        return $result;
    }

    public function clearItinerariesCache()
    {
        Cache::forget(self::ITINERARIES_ALL_CACHE_KEY);
    }
}
