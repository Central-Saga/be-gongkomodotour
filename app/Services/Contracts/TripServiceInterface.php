<?php

namespace App\Services\Contracts;

interface TripServiceInterface
{
    /**
     * Mengambil semua trips.
     *
     * @return mixed
     */
    public function getAllTrips();

    /**
     * Mengambil trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripById($id);

    /**
     * Mengambil trip berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripByName($name);

    /**
     * Mengambil trip berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripByStatus($status);

    /**
     * Mengambil semua trips yang aktif.
     *
     * @return mixed
     */
    public function getOpenTrips();

    /**
     * Mengambil semua trips yang tidak aktif.
     *
     * @return mixed
     */
    public function getPrivateTrips();

    /**
     * Mengambil semua trips yang aktif.
     *
     * @return mixed
     */
    public function getActiveTrips();

    /**
     * Mengambil semua trips yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTrips();

    /**
     * Mengambil trip berdasarkan has_boat.
     *
     * @param bool $hasBoat
     * @return mixed
     */
    public function getTripByHasBoat($hasBoat);

    /**
     * Mengambil trip berdasarkan destination_count.
     *
     * @param int $destinationCount
     * @return mixed
     */
    public function getTripByDestinationCount($destinationCount);

    /**
     * Mengambil trip berdasarkan range destination_count.
     *
     * @param int $min
     * @param int $max
     * @return mixed
     */
    public function getTripByDestinationCountRange($min, $max);

    /**
     * Membuat trip baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTrip(array $data);

    /**
     * Memperbarui trip berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTrip($id, array $data);

    /**
     * Menghapus trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTrip($id);

    /**
     * Mengupdate status trip.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripStatus($id, $status);
}
