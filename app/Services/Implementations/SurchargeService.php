<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\SurchargeServiceInterface;
use App\Repositories\Contracts\SurchargeRepositoryInterface;

class SurchargeService implements SurchargeServiceInterface
{
    protected $surchargeRepository;

    const SURCHARGE_ALL_CACHE_KEY = 'surcharges.all';

    public function __construct(SurchargeRepositoryInterface $surchargeRepository)
    {
        $this->surchargeRepository = $surchargeRepository;
    }

    public function getAllSurcharges()
    {
        $surcharges = Cache::remember(self::SURCHARGE_ALL_CACHE_KEY, 3600, function () {
            return $this->surchargeRepository->getAllSurcharges();
        });

        return $surcharges;
    }

    public function getSurchargeById($id)
    {
        return $this->surchargeRepository->getSurchargeById($id);
    }

    public function getSurchargeByName($name)
    {
        return $this->surchargeRepository->getSurchargeByName($name);
    }

    public function getSurchargeByStatus($status)
    {
        return $this->surchargeRepository->getSurchargeByStatus($status);
    }

    public function getSurchargeByTripId($tripId)
    {
        return $this->surchargeRepository->getSurchargeByTripId($tripId);
    }

    public function createSurcharge(array $data)
    {
        $result = $this->surchargeRepository->createSurcharge($data);
        $this->clearSurchargeCaches();
        return $result;
    }

    public function updateSurcharge($id, array $data)
    {
        $result = $this->surchargeRepository->updateSurcharge($id, $data);
        $this->clearSurchargeCaches();
        return $result;
    }

    public function deleteSurcharge($id)
    {
        $result = $this->surchargeRepository->deleteSurcharge($id);
        $this->clearSurchargeCaches();
        return $result;
    }

    public function updateSurchargeStatus($id, $status)
    {
        $result = $this->surchargeRepository->updateSurchargeStatus($id, $status);
        $this->clearSurchargeCaches();
        return $result;
    }

    public function clearSurchargeCaches()
    {
        Cache::forget(self::SURCHARGE_ALL_CACHE_KEY);
    }
}
