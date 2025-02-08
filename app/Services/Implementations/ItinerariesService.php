<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\ItinerariesServiceInterface;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;


class ItinerariesService implements ItinerariesServiceInterface
{
    protected $itinerariesRepository;

    const ITINERARIESS_ALL_CACHE_KEY = 'itinerariess.all';
    const ITINERARIESS_ACTIVE_CACHE_KEY = 'itinerariess.active';
    const ITINERARIESS_INACTIVE_CACHE_KEY = 'itinerariess.inactive';

    /**
     * Konstruktor ItinerariesService.
     *
     * @param ItinerariesRepositoryInterface $itinerariesRepository
     */
    public function __construct(ItinerariesRepositoryInterface $itinerariesRepository)
    {
        $this->itinerariesRepository = $itinerariesRepository;
    }

    /**
     * Mengambil semua itinerariess.
     *
     * @return mixed
     */
    public function getAllItineraries()
    {
        return Cache::remember(self::ITINERARIESS_ALL_CACHE_KEY, 3600, function () {
            return $this->itinerariesRepository->getAllItinerariess();
        });
    }

    /**
     * Mengambil itineraries berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getItinerariesById($id)
    {
        return $this->itinerariesRepository->getItinerariesById($id);
    }

    /**
     * Mengambil itineraries berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getItinerariesByName($name)
    {
        return $this->itinerariesRepository->getItinerariesByName($name);
    }

    /**
     * Mengambil itineraries berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getItinerariesByStatus($status)
    {
        return $this->itinerariesRepository->getItinerariesByStatus($status);
    }

    /**
     * Mengambil itinerariess dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveItinerariess()
    {
        return Cache::remember(self::ITINERARIESS_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->itinerariesRepository->getItinerariesByStatus('Aktif');
        });
    }

    /**
     * Mengambil itinerariess dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveItinerariess()
    {
        return Cache::remember(self::ITINERARIESS_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->itinerariesRepository->getItinerariesByStatus('Non Aktif');
        });
    }

    /**
     * Membuat itineraries baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createItineraries(array $data)
    {
        $result = $this->itinerariesRepository->createItineraries($data);
        Cache::forget(self::ITINERARIESS_ALL_CACHE_KEY);
        Cache::forget(self::ITINERARIESS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui itineraries berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateItineraries($id, array $data)
    {
        $result = $this->itinerariesRepository->updateItineraries($id, $data);
        Cache::forget(self::ITINERARIESS_ALL_CACHE_KEY);
        Cache::forget(self::ITINERARIESS_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus itineraries berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteItineraries($id)
    {
        $result = $this->itinerariesRepository->deleteItineraries($id);
        Cache::forget(self::ITINERARIESS_ALL_CACHE_KEY);
        Cache::forget(self::ITINERARIESS_ACTIVE_CACHE_KEY);

        return $result;
    }
}
