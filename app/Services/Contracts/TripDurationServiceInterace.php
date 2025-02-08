<?php

namespace App\Services\Contracts;

interface TripDurationServiceInterface
{
    /**
     * Mengambil semua tripdurations.
     *
     * @return mixed
     */
    public function getAllTripDurations();

    /**
     * Mengambil tripduration berdasarkan ID.
     *z
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
     * Mengambil semua tripdurations yang aktif.
     *
     * @return mixed
     */
    public function getActiveTripDurations();

    /**
     * Mengambil semua tripdurations yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripDurations();

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
}
