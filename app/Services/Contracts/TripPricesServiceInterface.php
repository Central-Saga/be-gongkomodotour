<?php

namespace App\Services\Contracts;

interface TripPricesServiceInterface
{
    /**
     * Mengambil semua harga trip.
     *
     * @return mixed
     */
    public function getAllTripPrices();

    /**
     * Mengambil harga trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTripPriceById($id);

    /**
     * Mengambil harga trip berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripPriceByName($name);

    /**
     * Mengambil harga trip berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripPriceByStatus($status);

    /**
     * Mengambil harga trip berdasarkan status aktif.
     *
     * @return mixed
     */
    public function getActiveTripPrices();

    /**
     * Mengambil harga trip berdasarkan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripPrices();

    /**
     * Mengambil harga trip berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getTripPriceByTripId($trip_id);

    /**
     * Membuat harga trip baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripPrice(array $data);

    /**
     * Memperbarui harga trip berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripPrice($id, array $data);

    /**
     * Menghapus harga trip berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripPrice($id);

    /**
     * Mengupdate status harga trip.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripPriceStatus($id, $status);
}
