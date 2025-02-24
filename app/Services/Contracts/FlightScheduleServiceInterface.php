<?php

namespace App\Services\Contracts;

interface FlightScheduleServiceInterface
{
    /**
     * Mengambil semua jadwal penerbangan.
     *
     * @return mixed
     */
    public function getAllFlightSchedules();

    /**
     * Mengambil jadwal penerbangan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFlightScheduleById($id);

    /**
     * Mengambil jadwal penerbangan berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFlightScheduleByName($name);

    /**
     * Mengambil jadwal penerbangan berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFlightScheduleByStatus($status);

    /**
     * Mengambil jadwal penerbangan berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getFlightScheduleByTripId($trip_id);

    /**
     * Membuat jadwal penerbangan baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFlightSchedule(array $data);

    /**
     * Memperbarui jadwal penerbangan berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFlightSchedule($id, array $data);

    /**
     * Menghapus jadwal penerbangan berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFlightSchedule($id);

    /**
     * Mengupdate status jadwal penerbangan.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateFlightScheduleStatus($id, $status);
}
