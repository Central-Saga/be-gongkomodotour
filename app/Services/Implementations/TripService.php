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
    const TRIP_OPEN_CACHE_KEY = 'trip.open';
    const TRIP_PRIVATE_CACHE_KEY = 'trip.private';

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
    public function getOpenTrips()
    {
        return Cache::remember(self::TRIP_OPEN_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getTripByType('Open Trip');
        });
    }

    /**
     * Mengambil trips dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getPrivateTrips()
    {
        return Cache::remember(self::TRIP_PRIVATE_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getTripByType('Private Trip');
        });
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

            // Update data trip utama secara parsial
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

            // Update itineraries secara parsial
            if (isset($data['itineraries'])) {
                $payloadItineraryIds = [];
                foreach ($data['itineraries'] as $itineraryData) {
                    $itineraryData['trip_id'] = $trip->id;
                    if (isset($itineraryData['id'])) {
                        // Update itinerary yang sudah ada
                        $this->itinerariesRepository->updateItineraries($itineraryData['id'], $itineraryData);
                        $payloadItineraryIds[] = $itineraryData['id'];
                    } else {
                        // Buat itinerary baru
                        $newItinerary = $this->itinerariesRepository->createItineraries($itineraryData);
                        if ($newItinerary && isset($newItinerary->id)) {
                            $payloadItineraryIds[] = $newItinerary->id;
                        }
                    }
                }
                // Hapus itinerary yang tidak terdapat pada payload update
                $this->itinerariesRepository->deleteItinerariesNotIn($trip->id, $payloadItineraryIds);
            }

            // Update flight schedules secara parsial
            if (isset($data['flight_schedules'])) {
                $payloadFlightScheduleIds = [];
                foreach ($data['flight_schedules'] as $scheduleData) {
                    $scheduleData['trip_id'] = $trip->id;
                    if (isset($scheduleData['id'])) {
                        $this->flightScheduleRepository->updateFlightSchedule($scheduleData['id'], $scheduleData);
                        $payloadFlightScheduleIds[] = $scheduleData['id'];
                    } else {
                        $newSchedule = $this->flightScheduleRepository->createFlightSchedule($scheduleData);
                        if ($newSchedule && isset($newSchedule->id)) {
                            $payloadFlightScheduleIds[] = $newSchedule->id;
                        }
                    }
                }
                // Hapus flight schedule yang tidak terdapat pada payload update
                $this->flightScheduleRepository->deleteFlightScheduleNotIn($trip->id, $payloadFlightScheduleIds);
            }

            // Update trip durations beserta trip prices secara parsial
            if (isset($data['trip_durations'])) {
                $payloadTripDurationIds = [];
                foreach ($data['trip_durations'] as $durationData) {
                    $durationData['trip_id'] = $trip->id;
                    if (isset($durationData['id'])) {
                        $tripDuration = $this->tripDurationRepository->updateTripDuration($durationData['id'], $durationData);
                        $payloadTripDurationIds[] = $durationData['id'];
                    } else {
                        $tripDuration = $this->tripDurationRepository->createTripDuration($durationData);
                        if ($tripDuration && isset($tripDuration->id)) {
                            $payloadTripDurationIds[] = $tripDuration->id;
                        }
                    }
                    if (!$tripDuration || !isset($tripDuration->id)) {
                        throw new \Exception("Trip duration update failed: repository returned invalid trip duration object.");
                    }

                    // Update trip prices untuk masing-masing trip duration
                    if (isset($durationData['prices'])) {
                        $payloadPriceIds = [];
                        foreach ($durationData['prices'] as $priceData) {
                            $priceData['trip_duration_id'] = $tripDuration->id;
                            if (isset($priceData['id'])) {
                                $this->tripPricesRepository->updateTripPrices($priceData['id'], $priceData);
                                $payloadPriceIds[] = $priceData['id'];
                            } else {
                                $newPrice = $this->tripPricesRepository->createTripPrices($priceData);
                                if ($newPrice && isset($newPrice->id)) {
                                    $payloadPriceIds[] = $newPrice->id;
                                }
                            }
                        }
                        // Hapus trip prices yang tidak terdapat dalam payload update
                        $this->tripPricesRepository->deleteTripPricesNotIn($tripDuration->id, $payloadPriceIds);
                    }
                }
                // Hapus trip durations yang tidak terdapat dalam payload update
                $this->tripDurationRepository->deleteTripDurationNotIn($trip->id, $payloadTripDurationIds);
            }

            DB::commit();

            // Clear cache yang terkait
            $this->clearTripCaches($id);

            return $trip->fresh(['itineraries', 'flightSchedule', 'tripDuration.tripPrices']);
        } catch (\Exception $e) {
            DB::rollBack();
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
        Cache::forget(self::TRIP_OPEN_CACHE_KEY);
        Cache::forget(self::TRIP_PRIVATE_CACHE_KEY);

        if ($tripId) {
            Cache::forget(self::TRIP_DETAIL_CACHE_KEY . $tripId);
        }
    }
}
