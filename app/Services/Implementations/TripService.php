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
use App\Repositories\Contracts\AdditionalFeeRepositoryInterface;
use App\Repositories\Contracts\SurchargeRepositoryInterface;
use Illuminate\Support\Arr;
use App\Services\Contracts\AssetServiceInterface;

class TripService implements TripServiceInterface
{
    protected $tripRepository;
    protected $itinerariesRepository;
    protected $flightScheduleRepository;
    protected $tripDurationRepository;
    protected $tripPricesRepository;
    protected $additionalFeeRepository;
    protected $surchargeRepository;
    protected $assetService;

    const TRIPS_ALL_CACHE_KEY = 'trips.all';
    const TRIPS_ACTIVE_CACHE_KEY = 'trips.active';
    const TRIPS_INACTIVE_CACHE_KEY = 'trips.inactive';
    const TRIP_DETAIL_CACHE_KEY = 'trip.detail.';
    const TRIP_OPEN_CACHE_KEY = 'trip.open';
    const TRIP_PRIVATE_CACHE_KEY = 'trip.private';
    const TRIPS_HIGHLIGHTED_CACHE_KEY = 'trips.highlighted';

    /**
     * Konstruktor TripService.
     *
     * @param TripRepositoryInterface $tripRepository
     */
    public function __construct(
        TripRepositoryInterface $tripRepository,
        ItinerariesRepositoryInterface $itinerariesRepository,
        FlightScheduleRepositoryInterface $flightScheduleRepository,
        TripDurationRepositoryInterface $tripDurationRepository,
        TripPricesRepositoryInterface $tripPricesRepository,
        AdditionalFeeRepositoryInterface $additionalFeeRepository,
        SurchargeRepositoryInterface $surchargeRepository,
        AssetServiceInterface $assetService
    ) {
        $this->tripRepository = $tripRepository;
        $this->itinerariesRepository = $itinerariesRepository;
        $this->flightScheduleRepository = $flightScheduleRepository;
        $this->tripDurationRepository = $tripDurationRepository;
        $this->tripPricesRepository = $tripPricesRepository;
        $this->additionalFeeRepository = $additionalFeeRepository;
        $this->surchargeRepository = $surchargeRepository;
        $this->assetService = $assetService;
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
     * Mengambil trips yang dihighlight.
     *
     * @return mixed
     */
    public function getHighlightedTrips()
    {
        return Cache::remember(self::TRIPS_HIGHLIGHTED_CACHE_KEY, 3600, function () {
            return $this->tripRepository->getHighlightedTrips();
        });
    }

    /**
     * Mengambil trip berdasarkan has_boat.
     *
     * @param bool $hasBoat
     * @return mixed
     */
    public function getTripByHasBoat($hasBoat)
    {
        $cacheKey = 'trips.has_boat.' . ($hasBoat ? 'true' : 'false');
        return Cache::remember($cacheKey, 3600, function () use ($hasBoat) {
            return $this->tripRepository->getTripByHasBoat($hasBoat);
        });
    }

    /**
     * Mengambil trip berdasarkan destination_count.
     *
     * @param int $destinationCount
     * @return mixed
     */
    public function getTripByDestinationCount($destinationCount)
    {
        $cacheKey = 'trips.destination_count.' . $destinationCount;
        return Cache::remember($cacheKey, 3600, function () use ($destinationCount) {
            return $this->tripRepository->getTripByDestinationCount($destinationCount);
        });
    }

    /**
     * Mengambil trip berdasarkan range destination_count.
     *
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function getTripByDestinationCountRange($min, $max)
    {
        $cacheKey = 'trips.destination_count.range.' . $min . '.' . $max;
        return Cache::remember($cacheKey, 3600, function () use ($min, $max) {
            return $this->tripRepository->getTripByDestinationCountRange($min, $max);
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
                'region',
                'start_time',
                'end_time',
                'meeting_point',
                'type',
                'status',
                'is_highlight',
                'has_boat',
                'has_hotel',
                'destination_count'
            ]);
            $trip = $this->tripRepository->createTrip($tripData);
            Log::info($trip);

            // Buat trip durations beserta itineraries dan trip prices jika ada
            if (isset($data['trip_durations'])) {
                foreach ($data['trip_durations'] as $duration) {
                    $duration['trip_id'] = $trip->id;
                    $tripDuration = $this->tripDurationRepository->createTripDuration($duration);
                    if (!$tripDuration || !isset($tripDuration->id)) {
                        throw new \Exception("Trip duration creation failed: repository returned invalid trip duration object.");
                    }

                    // Buat itineraries untuk trip duration ini
                    if (isset($duration['itineraries'])) {
                        foreach ($duration['itineraries'] as $itinerary) {
                            $itinerary['trip_duration_id'] = $tripDuration->id;
                            $this->itinerariesRepository->createItineraries($itinerary);
                        }
                    }

                    if (isset($duration['prices'])) {
                        foreach ($duration['prices'] as $price) {
                            $price['trip_duration_id'] = $tripDuration->id;
                            $this->tripPricesRepository->createTripPrices($price);
                        }
                    }
                }
            }

            // Buat flight schedules jika ada
            if (isset($data['flight_schedules'])) {
                foreach ($data['flight_schedules'] as $flightSchedule) {
                    $flightSchedule['trip_id'] = $trip->id;
                    $this->flightScheduleRepository->createFlightSchedule($flightSchedule);
                }
            }

            // Jika request memiliki additional fees, buat masing-masing additional fee
            if (isset($data['additional_fees'])) {
                foreach ($data['additional_fees'] as $fee) {
                    $fee['trip_id'] = $trip->id;
                    $this->additionalFeeRepository->createAdditionalFee($fee);
                }
            }

            // Jika request memiliki surcharges, buat masing-masing surcharge
            if (isset($data['surcharges'])) {
                foreach ($data['surcharges'] as $surcharge) {
                    $surcharge['trip_id'] = $trip->id;
                    $this->surchargeRepository->createSurcharge($surcharge);
                }
            }

            // Jika request memiliki assets, buat masing-masing asset
            if (isset($data['assets'])) {
                foreach ($data['assets'] as $asset) {
                    $assetData = array_merge($asset, [
                        'model_type' => 'trip',
                        'model_id' => $trip->id
                    ]);
                    $this->assetService->addAsset('trip', $trip->id, $assetData);
                }
            }

            DB::commit();

            // Clear all related caches
            $this->clearTripCaches();

            return $trip->fresh(['tripDuration.itineraries', 'flightSchedule', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets']);
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
                'region',
                'start_time',
                'end_time',
                'meeting_point',
                'type',
                'status',
                'is_highlight',
                'has_boat',
                'has_hotel',
                'destination_count'
            ]);
            $trip = $this->tripRepository->updateTrip($id, $tripData);
            if (!$trip || !isset($trip->id)) {
                throw new \Exception("Trip update failed: repository returned invalid trip object.");
            }

            // Update trip durations beserta itineraries dan trip prices secara parsial
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

                    // Update itineraries untuk trip duration ini
                    if (isset($durationData['itineraries'])) {
                        $payloadItineraryIds = [];
                        foreach ($durationData['itineraries'] as $itineraryData) {
                            $itineraryData['trip_duration_id'] = $tripDuration->id;
                            if (isset($itineraryData['id'])) {
                                $this->itinerariesRepository->updateItineraries($itineraryData['id'], $itineraryData);
                                $payloadItineraryIds[] = $itineraryData['id'];
                            } else {
                                $newItinerary = $this->itinerariesRepository->createItineraries($itineraryData);
                                if ($newItinerary && isset($newItinerary->id)) {
                                    $payloadItineraryIds[] = $newItinerary->id;
                                }
                            }
                        }
                        $this->itinerariesRepository->deleteItinerariesNotIn($tripDuration->id, $payloadItineraryIds);
                    }

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
                        $this->tripPricesRepository->deleteTripPricesNotIn($tripDuration->id, $payloadPriceIds);
                    }
                }
                $this->tripDurationRepository->deleteTripDurationNotIn($trip->id, $payloadTripDurationIds);
            }

            // Update flight schedules secara parsial jika ada di payload
            if (isset($data['flight_schedules'])) {
                $payloadFlightScheduleIds = [];
                foreach ($data['flight_schedules'] as $flightScheduleData) {
                    $flightScheduleData['trip_id'] = $trip->id;
                    if (isset($flightScheduleData['id'])) {
                        $this->flightScheduleRepository->updateFlightSchedule($flightScheduleData['id'], $flightScheduleData);
                        $payloadFlightScheduleIds[] = $flightScheduleData['id'];
                    } else {
                        $newFlightSchedule = $this->flightScheduleRepository->createFlightSchedule($flightScheduleData);
                        if ($newFlightSchedule && isset($newFlightSchedule->id)) {
                            $payloadFlightScheduleIds[] = $newFlightSchedule->id;
                        }
                    }
                }
                $this->flightScheduleRepository->deleteFlightScheduleNotIn($trip->id, $payloadFlightScheduleIds);
            }

            // Update additional fees secara parsial jika ada di payload
            if (isset($data['additional_fees'])) {
                $payloadAdditionalFeeIds = [];
                foreach ($data['additional_fees'] as $feeData) {
                    $feeData['trip_id'] = $trip->id;
                    if (isset($feeData['id'])) {
                        $this->additionalFeeRepository->updateAdditionalFee($feeData['id'], $feeData);
                        $payloadAdditionalFeeIds[] = $feeData['id'];
                    } else {
                        $newFee = $this->additionalFeeRepository->createAdditionalFee($feeData);
                        if ($newFee && isset($newFee->id)) {
                            $payloadAdditionalFeeIds[] = $newFee->id;
                        }
                    }
                }
                $this->additionalFeeRepository->deleteAdditionalFeesNotIn($trip->id, $payloadAdditionalFeeIds);
            }

            // Update surcharges secara parsial jika ada di payload
            if (isset($data['surcharges'])) {
                $payloadSurchargeIds = [];
                foreach ($data['surcharges'] as $surchargeData) {
                    $surchargeData['trip_id'] = $trip->id;
                    if (isset($surchargeData['id'])) {
                        $this->surchargeRepository->updateSurcharge($surchargeData['id'], $surchargeData);
                        $payloadSurchargeIds[] = $surchargeData['id'];
                    } else {
                        $newSurcharge = $this->surchargeRepository->createSurcharge($surchargeData);
                        if ($newSurcharge && isset($newSurcharge->id)) {
                            $payloadSurchargeIds[] = $newSurcharge->id;
                        }
                    }
                }
                $this->surchargeRepository->deleteSurchargesNotIn($trip->id, $payloadSurchargeIds);
            }

            // Update assets secara parsial jika ada di payload
            if (isset($data['assets'])) {
                $payloadAssetIds = [];
                foreach ($data['assets'] as $assetData) {
                    $assetData['model_type'] = 'trip';
                    $assetData['model_id'] = $trip->id;

                    if (isset($assetData['id'])) {
                        $this->assetService->updateAsset($assetData['id'], $assetData);
                        $payloadAssetIds[] = $assetData['id'];
                    } else {
                        $newAsset = $this->assetService->addAsset('trip', $trip->id, $assetData);
                        if ($newAsset && isset($newAsset->id)) {
                            $payloadAssetIds[] = $newAsset->id;
                        }
                    }
                }

                // Hapus asset yang tidak ada di payload
                $existingAssets = $trip->assets()->pluck('id')->toArray();
                $assetsToDelete = array_diff($existingAssets, $payloadAssetIds);
                foreach ($assetsToDelete as $assetId) {
                    $this->assetService->deleteAsset($assetId);
                }
            }

            DB::commit();

            // Clear cache yang terkait
            $this->clearTripCaches($id);

            return $trip->fresh(['tripDuration.itineraries', 'flightSchedule', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets']);
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

            // Delete related assets
            $trip = $this->getTripById($id);
            if ($trip) {
                $trip->assets()->delete();
            }

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

    public function updateTripStatus($id, $status)
    {
        $trip = $this->getTripById($id);

        if ($trip) {
            $result = $this->tripRepository->updateTripStatus($id, $status);

            $this->clearTripCaches($id);

            return $result;
        }

        return null;
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
        Cache::forget(self::TRIPS_HIGHLIGHTED_CACHE_KEY);

        if ($tripId) {
            Cache::forget(self::TRIP_DETAIL_CACHE_KEY . $tripId);
        }
    }
}
