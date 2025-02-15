<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\FlightScheduleServiceInterface;
use App\Repositories\Contracts\FlightScheduleRepositoryInterface;


class FlightScheduleService implements FlightScheduleServiceInterface
{
    protected $flightScheduleRepository;

    const FLIGHTSCHEDULES_ALL_CACHE_KEY = 'flightschedules.all';
    const FLIGHTSCHEDULES_ACTIVE_CACHE_KEY = 'flightschedules.active';
    const FLIGHTSCHEDULES_INACTIVE_CACHE_KEY = 'flightschedules.inactive';

    /**
     * Konstruktor FlightScheduleService.
     *
     * @param FlightScheduleRepositoryInterface $flightscheduleRepository
     */
    public function __construct(FlightScheduleRepositoryInterface $flightscheduleRepository)
    {
        $this->flightScheduleRepository = $flightscheduleRepository;
    }

    /**
     * Mengambil semua flightschedules.
     *
     * @return mixed
     */
    public function getAllFlightSchedules()
    {
        return Cache::remember(self::FLIGHTSCHEDULES_ALL_CACHE_KEY, 3600, function () {
            return $this->flightScheduleRepository->getAllFlightSchedules();
        });
    }

    /**
     * Mengambil flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFlightScheduleById($id)
    {
        return $this->flightScheduleRepository->getFlightScheduleById($id);
    }

    /**
     * Mengambil flightschedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFlightScheduleByName($name)
    {
        return $this->flightScheduleRepository->getFlightScheduleByName($name);
    }

    /**
     * Mengambil flightschedule berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFlightScheduleByStatus($status)
    {
        return $this->flightScheduleRepository->getFlightScheduleByStatus($status);
    }

    /**
     * Mengambil flightschedules dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveFlightSchedules()
    {
        return Cache::remember(self::FLIGHTSCHEDULES_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->flightScheduleRepository->getFlightScheduleByStatus('Aktif');
        });
    }

    /**
     * Mengambil flightschedules dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveFlightSchedules()
    {
        return Cache::remember(self::FLIGHTSCHEDULES_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->flightScheduleRepository->getFlightScheduleByStatus('Non Aktif');
        });
    }

    /**
     * Membuat flightschedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFlightSchedule(array $data)
    {
        $result = $this->flightScheduleRepository->createFlightSchedule($data);
        Cache::forget(self::FLIGHTSCHEDULES_ALL_CACHE_KEY);
        Cache::forget(self::FLIGHTSCHEDULES_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui flightschedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFlightSchedule($id, array $data)
    {
        $result = $this->flightScheduleRepository->updateFlightSchedule($id, $data);
        Cache::forget(self::FLIGHTSCHEDULES_ALL_CACHE_KEY);
        Cache::forget(self::FLIGHTSCHEDULES_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteFlightSchedule($id)
    {
        $result = $this->flightScheduleRepository->deleteFlightSchedule($id);
        Cache::forget(self::FLIGHTSCHEDULES_ALL_CACHE_KEY);
        Cache::forget(self::FLIGHTSCHEDULES_ACTIVE_CACHE_KEY);

        return $result;
    }
}
