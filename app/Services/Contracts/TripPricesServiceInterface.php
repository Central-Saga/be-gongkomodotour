<?php

namespace App\Services\Contracts;

interface TripPricesServiceInterface
{
    /**
     * Mengambil semua trippricess.
     *
     * @return mixed
     */
    public function getAllTripPrices();

    /**
     * Mengambil tripprices berdasarkan ID.
     *z
     * @param int $id
     * @return mixed
     */
    public function getTripPricesById($id);

    /**
     * Mengambil tripprices berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTripPricesByName($name);

    /**
     * Mengambil tripprices berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTripPricesByStatus($status);

    /**
     * Mengambil semua trippricess yang aktif.
     *
     * @return mixed
     */
    public function getActiveTripPricess();

    /**
     * Mengambil semua trippricess yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveTripPricess();

    /**
     * Membuat tripprices baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTripPrices(array $data);

    /**
     * Memperbarui tripprices berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTripPrices($id, array $data);

    /**
     * Menghapus tripprices berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTripPrices($id);
}
