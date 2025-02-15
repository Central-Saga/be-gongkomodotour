<?php

namespace App\Services\Contracts;

interface FlightScheduleServiceInterface
{
    /**
     * Mengambil semua flightschedules.
     *
     * @return mixed
     */
    public function getAllFlightSchedules();

    /**
     * Mengambil flightschedule berdasarkan ID.
     *z
     * @param int $id
     * @return mixed
     */
    public function getFlightScheduleById($id);

    /**
     * Mengambil flightschedule berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFlightScheduleByName($name);

    /**
     * Mengambil flightschedule berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFlightScheduleByStatus($status);

    /**
     * Mengambil semua flightschedules yang aktif.
     *
     * @return mixed
     */
    public function getActiveFlightSchedules();

    /**
     * Mengambil semua flightschedules yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveFlightSchedules();

    /**
     * Membuat flightschedule baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFlightSchedule(array $data);

    /**
     * Memperbarui flightschedule berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFlightSchedule($id, array $data);

    /**
     * Menghapus flightschedule berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFlightSchedule($id);
}
