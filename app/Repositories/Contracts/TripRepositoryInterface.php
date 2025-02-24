<?php

namespace App\Repositories\Contracts;

interface TripRepositoryInterface
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
     * Mengambil trip berdasarkan type.
     *
     * @param string $type
     * @return mixed
     */
    public function getTripByType($type);

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
     * Mengupdate trip status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripStatus($id, $status);
}
