<?php

namespace App\Repositories\Contracts;

interface HotelOccupanciesRepositoryInterface
{
    /**
     * Mengambil semua hotelOccupanciess.
     *
     * @return mixed
     */
    public function getAllHotelOccupancies();

    /**
     * Mengambil hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelOccupanciesById($id);

    /**
     * Mengambil hotelOccupancies berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelOccupanciesByName($name);

    /**
     * Mengambil hotelOccupancies berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelOccupanciesByStatus($status);

    /**
     * Membuat hotelOccupancies baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelOccupancies(array $data);

    /**
     * Memperbarui hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateHotelOccupancies($id, array $data);

    /**
     * Menghapus hotelOccupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteHotelOccupancies($id);

    /**
     * Mengupdate hotelOccupancies status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelOccupanciesStatus($id, $status);
}
