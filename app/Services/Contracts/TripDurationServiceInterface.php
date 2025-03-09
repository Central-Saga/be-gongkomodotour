<?php

namespace App\Services\Contracts;

interface TripDurationServiceInterface
{
    /**
     * Mengambil semua trip duration.
     *
     * @return mixed
     */
    public function getAllTripDurations();

    /**
     * Mengambil trip duration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripDurationById($id);

    /**
     * Mengambil trip duration berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripDurationByName($name);

    /**
     * Mengambil trip duration berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripDurationByStatus($status);

    /**
     * Mengambil trip duration berdasarkan status aktif.
     *
     * @return mixed
     */
    public function getActiveTripDurations();

    /**
     * Mengambil trip duration berdasarkan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripDurations();

    /**
     * Mengambil trip duration berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getTripDurationByTripId($trip_id);

    /**
     * Membuat trip duration baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripDuration(array $data);

    /**
     * Memperbarui trip duration berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripDuration($id, array $data);

    /**
     * Menghapus trip duration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripDuration($id);

    /**
     * Mengupdate status trip duration.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripDurationStatus($id, $status);
}
