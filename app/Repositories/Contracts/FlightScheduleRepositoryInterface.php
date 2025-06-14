<?php

namespace App\Repositories\Contracts;

interface FlightScheduleRepositoryInterface
{
    /**
     * Mengambil semua flightSchedule.
     *
     * @return mixed
     */
    public function getAllFlightSchedules();

    /**
     * Mengambil flightSchedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFlightScheduleById($id);

    /**
     * Mengambil flightSchedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFlightScheduleByName($name);

    /**
     * Mengambil flightSchedule berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFlightScheduleByStatus($status);

    /**
     * Membuat flightSchedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFlightSchedule(array $data);

    /**
     * Memperbarui flightSchedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFlightSchedule($id, array $data);

    /**
     * Menghapus flightSchedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFlightSchedule($id);

    /**
     * Menghapus flightSchedule yang tidak ada di trip.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteFlightScheduleNotIn($trip_id, $existing_id);

    /**
     * Mengupdate flightSchedule status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateFlightScheduleStatus($id, $status);
}
