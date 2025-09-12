<?php

namespace App\Repositories\Eloquent;

use App\Models\FlightSchedule;
use App\Repositories\Contracts\FlightScheduleRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FlightScheduleRepository implements FlightScheduleRepositoryInterface
{
    /**
     * @var FlightSchedule
     */
    protected $flightschedule;

    /**
     * Konstruktor FlightScheduleRepository.
     *
     * @param FlightSchedule $flightschedule
     */
    public function __construct(FlightSchedule $flightschedule)
    {
        $this->flightschedule = $flightschedule;
    }

    /**
     * Mengambil semua flightschedules.
     *
     * @return mixed
     */
    public function getAllFlightSchedules()
    {
        return $this->flightschedule->all();
    }

    /**
     * Mengambil flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFlightScheduleById($id)
    {
        try {
            // Mengambil flightschedule berdasarkan ID, handle jika tidak ditemukan
            return $this->flightschedule->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("FlightSchedule with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil flightschedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFlightScheduleByName($name)
    {
        return $this->flightschedule->where('name', $name)->first();
    }

    /**
     * Mengambil flightschedule berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFlightScheduleByStatus($status)
    {
        return $this->flightschedule->where('status', $status)->get();
    }

    /**
     * Membuat flightschedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFlightSchedule(array $data)
    {
        try {
            return $this->flightschedule->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create flightschedule: {$e->getMessage()}");
            return null;
        }
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
        $flightschedule = $this->findFlightSchedule($id);

        if ($flightschedule) {
            try {
                $flightschedule->update($data);
                return $flightschedule;
            } catch (\Exception $e) {
                Log::error("Failed to update flightschedule with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFlightSchedule($id)
    {
        $flightschedule = $this->findFlightSchedule($id);

        if ($flightschedule) {
            try {
                $flightschedule->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete flightschedule with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findFlightSchedule($id)
    {
        try {
            return $this->flightschedule->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("FlightSchedule with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Menghapus flightSchedule yang tidak ada di trip.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteFlightScheduleNotIn($trip_id, $existing_id)
    {
        try {
            return $this->flightschedule->where('trip_id', $trip_id)->whereNotIn('id', $existing_id)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete flightSchedule with trip_id {$trip_id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Mengupdate flight schedule status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateFlightScheduleStatus($id, $status)
    {
        $flightschedule = $this->findFlightSchedule($id);

        if ($flightschedule) {
            $flightschedule->status = $status;
            $flightschedule->save();
            return $flightschedule;
        }
        return null;
    }
}
