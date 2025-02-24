<?php

namespace App\Services\Implementations;

use App\Services\Contracts\FlightScheduleServiceInterface;
use App\Repositories\Contracts\FlightScheduleRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class FlightScheduleService implements FlightScheduleServiceInterface
{
    protected $flightScheduleRepository;

    const FLIGHT_SCHEDULE_ALL_CACHE_KEY = 'flight_schedules.all';

    public function __construct(FlightScheduleRepositoryInterface $flightScheduleRepository)
    {
        $this->flightScheduleRepository = $flightScheduleRepository;
    }

    public function getAllFlightSchedules()
    {
        $flightSchedules = Cache::remember(self::FLIGHT_SCHEDULE_ALL_CACHE_KEY, 3600, function () {
            return $this->flightScheduleRepository->getAllFlightSchedules();
        });

        return $flightSchedules;
    }

    public function getFlightScheduleById($id)
    {
        return $this->flightScheduleRepository->getFlightScheduleById($id);
    }

    public function getFlightScheduleByName($name)
    {
        return $this->flightScheduleRepository->getFlightScheduleByName($name);
    }

    public function getFlightScheduleByStatus($status)
    {
        return $this->flightScheduleRepository->getFlightScheduleByStatus($status);
    }

    public function getFlightScheduleByTripId($trip_id)
    {
        return $this->flightScheduleRepository->getFlightScheduleByTripId($trip_id);
    }

    public function createFlightSchedule(array $data)
    {
        $result = $this->flightScheduleRepository->createFlightSchedule($data);
        $this->clearFlightScheduleCaches();
        return $result;
    }

    public function updateFlightSchedule($id, array $data)
    {
        $result = $this->flightScheduleRepository->updateFlightSchedule($id, $data);
        $this->clearFlightScheduleCaches();
        return $result;
    }

    public function deleteFlightSchedule($id)
    {
        $result = $this->flightScheduleRepository->deleteFlightSchedule($id);
        $this->clearFlightScheduleCaches();
        return $result;
    }

    public function updateFlightScheduleStatus($id, $status)
    {
        $result = $this->flightScheduleRepository->updateFlightScheduleStatus($id, $status);
        $this->clearFlightScheduleCaches();
        return $result;
    }

    public function clearFlightScheduleCaches()
    {
        Cache::forget(self::FLIGHT_SCHEDULE_ALL_CACHE_KEY);
    }
}
