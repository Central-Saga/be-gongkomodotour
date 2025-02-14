<?php

namespace App\Repositories\Contracts;

interface TripDurationRepositoryInterface
{
    /**
     * Mengambil semua tripdurations.
     *
     * @return mixed
     */
    public function getAllTripDurations();

    /**
     * Mengambil tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripDurationById($id);

    /**
     * Mengambil tripduration berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripDurationByName($name);

    /**
     * Mengambil tripduration berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripDurationByStatus($status);

    /**
     * Membuat tripduration baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripDuration(array $data);

    /**
     * Memperbarui tripduration berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripDuration($id, array $data);

    /**
     * Menghapus tripduration berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripDuration($id);

    /**
     * Menghapus tripduration berdasarkan trip_id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteTripDurationByTripId($trip_id);
}
