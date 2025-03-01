<?php

namespace App\Services\Implementations;

use App\Services\Contracts\AdditionalFeeServiceInterface;
use App\Repositories\Contracts\AdditionalFeeRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class AdditionalFeeService implements AdditionalFeeServiceInterface
{
    protected $additionalFeeRepository;

    const ADDITIONAL_FEE_ALL_CACHE_KEY = 'additional_fees.all';
    const ADDITIONAL_FEE_ACTIVE_CACHE_KEY = 'additional_fees.active';
    const ADDITIONAL_FEE_INACTIVE_CACHE_KEY = 'additional_fees.inactive';

    public function __construct(AdditionalFeeRepositoryInterface $additionalFeeRepository)
    {
        $this->additionalFeeRepository = $additionalFeeRepository;
    }

    public function getAllAdditionalFees()
    {
        $additionalFees = Cache::remember(self::ADDITIONAL_FEE_ALL_CACHE_KEY, 3600, function () {
            return $this->additionalFeeRepository->getAllAdditionalFees();
        });

        return $additionalFees;
    }

    public function getAdditionalFeeById($id)
    {
        return $this->additionalFeeRepository->getAdditionalFeeById($id);
    }

    public function getAdditionalFeeByName($name)
    {
        return $this->additionalFeeRepository->getAdditionalFeeByName($name);
    }

    public function getAdditionalFeeByStatus($status)
    {
        return $this->additionalFeeRepository->getAdditionalFeeByStatus($status);
    }

    public function getActiveAdditionalFees()
    {
        $additionalFees = Cache::remember(self::ADDITIONAL_FEE_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->additionalFeeRepository->getAdditionalFeeByStatus('Aktif');
        });

        return $additionalFees;
    }

    public function getInactiveAdditionalFees()
    {
        $additionalFees = Cache::remember(self::ADDITIONAL_FEE_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->additionalFeeRepository->getAdditionalFeeByStatus('Non Aktif');
        });

        return $additionalFees;
    }

    public function createAdditionalFee(array $data)
    {
        $additionalFee = $this->additionalFeeRepository->createAdditionalFee($data);
        $this->clearAdditionalFeeCaches();
        return $additionalFee;
    }

    public function updateAdditionalFee($id, array $data)
    {
        $additionalFee = $this->additionalFeeRepository->updateAdditionalFee($id, $data);
        $this->clearAdditionalFeeCaches();
        return $additionalFee;
    }

    public function deleteAdditionalFee($id)
    {
        $this->additionalFeeRepository->deleteAdditionalFee($id);
        $this->clearAdditionalFeeCaches();
    }

    public function updateAdditionalFeeStatus($id, $status)
    {
        $this->additionalFeeRepository->updateAdditionalFeeStatus($id, $status);
        $this->clearAdditionalFeeCaches();
    }

    public function clearAdditionalFeeCaches()
    {
        Cache::forget(self::ADDITIONAL_FEE_ALL_CACHE_KEY);
        Cache::forget(self::ADDITIONAL_FEE_ACTIVE_CACHE_KEY);
        Cache::forget(self::ADDITIONAL_FEE_INACTIVE_CACHE_KEY);
    }
}
