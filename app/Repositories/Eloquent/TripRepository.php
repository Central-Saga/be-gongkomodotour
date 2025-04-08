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
        return $this->trips->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')->get();
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
            return $this->trips->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Trip with ID {$id} not found.");
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
        return $this->trips->where('name', $name)->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')->first();
    }

    /**
     * Mengambil trip berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripByStatus($status)
    {
        return $this->trips->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')->where('status', $status)->get();
    }

    /**
     * Mengambil trip berdasarkan type.
     *
     * @param string $type
     * @return mixed
     */
    public function getTripByType($type)
    {
        return $this->trips->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')->where('type', $type)->get();
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
            return $this->trips->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create trip: {$e->getMessage()}");
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
        return $this->trips->with('itineraries', 'flightSchedule', 'tripDuration', 'tripDuration.tripPrices', 'additionalFees', 'surcharges', 'assets')
            ->where('is_highlight', true)
            ->where('status', 'Aktif')
            ->get();
    }
}
