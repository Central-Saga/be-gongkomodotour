<?php

namespace App\Repositories\Contracts;

interface TripPricesRepositoryInterface
{
    /**
     * Mengambil semua trippricess.
     *
     * @return mixed
     */
    public function getAllTripPrices();

    /**
     * Mengambil tripprices berdasarkan ID.
     *
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

    /**
     * Menghapus tripprices yang tidak ada di trip duration.
     *
     * @param int $trip_duration_id
     * @return mixed
     */
    public function deleteTripPricesNotIn($trip_duration_id, $existing_id);

    /**
     * Mengupdate tripprices status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateTripPricesStatus($id, $status);
}
