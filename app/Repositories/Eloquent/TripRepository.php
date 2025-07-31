<?php

namespace App\Repositories\Eloquent;

use App\Models\Trips;
use App\Repositories\Contracts\TripRepositoryInterface;
use Illuminate\Support\Facades\Log;

class TripRepository implements TripRepositoryInterface
{
    /**
     * @var Trips
     */
    protected $trips;

    /**
     * Konstruktor TripRepository.
     *
     * @param Trips $trip
     */
    public function __construct(Trips $trips)
    {
        $this->trips = $trips;
    }

    /**
     * Mengambil semua trips.
     *
     * @return mixed
     */
    public function getAllTrips()
    {
        $trips = $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->get();

        return $trips;
    }

    /**
     * Mengambil trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripById($id)
    {
        try {
            // Mengambil trip berdasarkan ID, handle jika tidak ditemukan
            $trip = $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->findOrFail($id);

            // Log data trip dan relasinya
            Log::info('Trip Data:', [
                'trip' => $trip->toArray(),
                'trip_duration' => $trip->tripDuration->toArray(),
                'itineraries' => $trip->tripDuration->flatMap->itineraries->toArray()
            ]);

            return $trip;
        } catch (ModelNotFoundException $e) {
            Log::error("Trip with ID {$id} not found.");
            return null;
        } catch (\Exception $e) {
            Log::error("Error getting trip with ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Mengambil trip berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripByName($name)
    {
        return $this->trips->where('name', $name)->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->first();
    }

    /**
     * Mengambil trip berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripByStatus($status)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->where('status', $status)->get();
    }

    /**
     * Mengambil trip berdasarkan type.
     *
     * @param string $type
     * @return mixed
     */
    public function getTripByType($type)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->where('type', $type)->get();
    }

    /**
     * Mengambil trip berdasarkan has_boat.
     *
     * @param bool $hasBoat
     * @return mixed
     */
    public function getTripByHasBoat($hasBoat)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])
            ->where('has_boat', $hasBoat)
            ->get();
    }

    /**
     * Mengambil trip berdasarkan destination_count.
     *
     * @param int $destinationCount
     * @return mixed
     */
    public function getTripByDestinationCount($destinationCount)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])
            ->where('destination_count', $destinationCount)
            ->get();
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
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])
            ->whereBetween('destination_count', [$min, $max])
            ->get();
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
            Log::info('Creating trip with data:', $data);
            $trip = $this->trips->create($data);
            Log::info('Trip created successfully:', ['trip_id' => $trip->id ?? 'null', 'trip' => $trip]);
            return $trip;
        } catch (\Exception $e) {
            Log::error("Failed to create trip: {$e->getMessage()}", [
                'data' => $data,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
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
        $trip = $this->findTrip($id);

        if ($trip) {
            try {
                $trip->update($data);
                return $trip;
            } catch (\Exception $e) {
                Log::error("Failed to update trip with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTrip($id)
    {
        $trip = $this->findTrip($id);

        if ($trip) {
            try {
                $trip->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete trip with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findTrip($id)
    {
        try {
            return $this->trips->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Trip with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate trip status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripStatus($id, $status)
    {
        $trip = $this->findTrip($id);

        if ($trip) {
            $trip->status = $status;
            $trip->save();
            return $trip;
        }
        return null;
    }

    /**
     * Mengambil trip yang dihighlight.
     *
     * @return mixed
     */
    public function getHighlightedTrips()
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])
            ->where('is_highlight', true)
            ->where('status', 'Aktif')
            ->get();
    }

    /**
     * Mengambil trip berdasarkan boat ID.
     *
     * @param int $boatId
     * @return mixed
     */
    public function getTripsByBoatId($boatId)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->where('boat_id', $boatId)->get();
    }

    /**
     * Mengambil trip dengan relasi boat.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripWithBoat($id)
    {
        return $this->trips->with(['boat', 'flightSchedule', 'tripDuration.itineraries', 'tripDuration.tripPrices', 'additionalFees', 'assets', 'testimonials'])->findOrFail($id);
    }
}
