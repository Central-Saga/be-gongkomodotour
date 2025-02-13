<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TripServiceInterface;
use App\Repositories\Contracts\TripRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Contracts\ItinerariesRepositoryInterface;
use App\Repositories\Contracts\FlightScheduleRepositoryInterface;
use App\Repositories\Contracts\TripDurationRepositoryInterface;
use App\Repositories\Contracts\TripPricesRepositoryInterface;
use Illuminate\Support\Arr;


class TripService implements TripServiceInterface
{
    protected $tripRepository;
    protected $itinerariesRepository;
    protected $flightScheduleRepository;
    protected $tripDurationRepository;
    protected $tripPricesRepository;

    const TRIPS_ALL_CACHE_KEY = 'trips.all';
    const TRIPS_ACTIVE_CACHE_KEY = 'trips.active';
    const TRIPS_INACTIVE_CACHE_KEY = 'trips.inactive';
    const TRIP_DETAIL_CACHE_KEY = 'trip.detail.';

    /**
     * Konstruktor TripService.
     *
     * @param TripRepositoryInterface $tripRepository
     */
    public function __construct(TripRepositoryInterface $tripRepository, ItinerariesRepositoryInterface $itinerariesRepository, FlightScheduleRepositoryInterface $flightScheduleRepository, TripDurationRepositoryInterface $tripDurationRepository, TripPricesRepositoryInterface $tripPricesRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->itinerariesRepository = $itinerariesRepository;
        $this->flightScheduleRepository = $flightScheduleRepository;
        $this->tripDurationRepository = $tripDurationRepository;
        $this->tripPricesRepository = $tripPricesRepository;
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
        return Cache::remember(self::TRIP_DETAIL_CACHE_KEY . $id, 3600, function () use ($id) {
            return $this->tripRepository->getTripById($id);
        });
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
        try {
            DB::beginTransaction();

            // Buat trip utama
            $tripData = Arr::only($data, [
                'name',
                'include',
                'exclude',
                'note',
                'duration',
                'start_time',
                'end_time',
                'meeting_point',
                'type',
                'status'
            ]);
            $trip = $this->tripRepository->createTrip($tripData);

            // Buat itineraries jika ada
            if (isset($data['itineraries'])) {
                foreach ($data['itineraries'] as $itinerary) {
                    $itinerary['trip_id'] = $trip->id;
                    $this->itinerariesRepository->createItineraries($itinerary);
                }
            }

            // Buat flight schedules jika ada
            if (isset($data['flight_schedules'])) {
                foreach ($data['flight_schedules'] as $schedule) {
                    $schedule['trip_id'] = $trip->id;
                    $this->flightScheduleRepository->createFlightSchedule($schedule);
                }
            }

            // Buat trip durations jika ada
            if (isset($data['trip_durations'])) {
                foreach ($data['trip_durations'] as $duration) {
                    $duration['trip_id'] = $trip->id;
                    $tripDuration = $this->tripDurationRepository->createTripDuration($duration);
                    if (!$tripDuration || !isset($tripDuration->id)) {
                        throw new \Exception("Trip duration creation failed: repository returned invalid trip duration object.");
                    }

                    // Buat trip prices untuk setiap duration jika ada
                    if (isset($duration['prices'])) {
                        foreach ($duration['prices'] as $price) {
                            $price['trip_duration_id'] = $tripDuration->id;
                            $this->tripPricesRepository->createTripPrices($price);
                        }
                    }
                }
            }

            DB::commit();

            // Clear all related caches
            $this->clearTripCaches();

            return $trip->fresh(['itineraries', 'flightSchedule', 'tripDuration.tripPrices']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create trip: {$e->getMessage()}");
            throw $e;
        }
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
        try {
            DB::beginTransaction();

            // Update trip utama
            $tripData = Arr::only($data, [
                'name',
                'include',
                'exclude',
                'note',
                'duration',
                'start_time',
                'end_time',
                'meeting_point',
                'type',
                'status'
            ]);
            $trip = $this->tripRepository->updateTrip($id, $tripData);
            if (!$trip || !isset($trip->id)) {
                throw new \Exception("Trip update failed: repository returned invalid trip object.");
            }

            // Update itineraries jika ada
            if (isset($data['itineraries'])) {
                // Hapus itineraries lama
                $this->itinerariesRepository->deleteItineraries($id);

                // Buat itineraries baru
                foreach ($data['itineraries'] as $itinerary) {
                    $itinerary['trip_id'] = $trip->id;
                    $this->itinerariesRepository->createItineraries($itinerary);
                }
            }

            // Update flight schedules jika ada
            if (isset($data['flight_schedules'])) {
                // Hapus flight schedules lama
                $this->flightScheduleRepository->deleteFlightSchedule($id);

                // Buat flight schedules baru
                foreach ($data['flight_schedules'] as $schedule) {
                    $schedule['trip_id'] = $trip->id;
                    $this->flightScheduleRepository->createFlightSchedule($schedule);
                }
            }

            // Update trip durations jika ada
            if (isset($data['trip_durations'])) {
                // Hapus trip durations lama
                $this->tripDurationRepository->deleteTripDuration($id);

                // Buat trip durations baru
                foreach ($data['trip_durations'] as $duration) {
                    $duration['trip_id'] = $trip->id;
                    $tripDuration = $this->tripDurationRepository->createTripDuration($duration);
                    if (!$tripDuration || !isset($tripDuration->id)) {
                        throw new \Exception("Trip duration update failed: repository returned invalid trip duration object.");
                    }

                    // Update trip prices jika ada
                    if (isset($duration['prices'])) {
                        foreach ($duration['prices'] as $price) {
                            $price['trip_duration_id'] = $tripDuration->id;
                            $this->tripPricesRepository->createTripPrices($price);
                        }
                    }
                }
            }

            DB::commit();

            // Clear all related caches
            $this->clearTripCaches($id);

            return $trip->fresh(['itineraries', 'flightSchedule', 'tripDuration.tripPrices']);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating trip: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus trip berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTrip($id)
    {
        try {
            DB::beginTransaction();

            // Delete related data first
            $this->itinerariesRepository->deleteItineraries($id);
            $this->flightScheduleRepository->deleteFlightSchedule($id);
            $this->tripDurationRepository->deleteTripDuration($id);

            // Delete the trip
            $result = $this->tripRepository->deleteTrip($id);

            DB::commit();

            // Clear all related caches
            $this->clearTripCaches($id);

            return $result;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting trip: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Clear all related trip caches
     *
     * @param int|null $tripId
     * @return void
     */
    private function clearTripCaches($tripId = null)
    {
        Cache::forget(self::TRIPS_ALL_CACHE_KEY);
        Cache::forget(self::TRIPS_ACTIVE_CACHE_KEY);
        Cache::forget(self::TRIPS_INACTIVE_CACHE_KEY);

        if ($tripId) {
            Cache::forget(self::TRIP_DETAIL_CACHE_KEY . $tripId);
        }
    }
}
