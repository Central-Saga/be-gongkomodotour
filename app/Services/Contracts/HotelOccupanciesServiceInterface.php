<?php

namespace App\Services\Contracts;

interface HotelOccupanciesServiceInterface
{
    /**
     * Mengambil semua hotelOcupanciess.
     *
     * @return mixed
     */
    public function getAllHotelOccupancies();

    /**
     * Mengambil hotelOcupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelOccupanciesById($id);

    /**
     * Mengambil hotelOcupancies berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelOccupanciesByName($name);

    /**
     * Mengambil hotelOcupancies berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelOccupanciesByStatus($status);

    /**
     * Mengambil semua hotelOcupanciess yang aktif.
     *
     * @return mixed
     */
    public function getActiveHotelOccupancies();

    /**
     * Mengambil semua hotelOcupanciess yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveHotelOccupancies();

    /**
     * Membuat hotelOcupancies baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelOccupancies(array $data);

    /**
     * Memperbarui hotelOcupancies berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateHotelOccupancies($id, array $data);

    /**
     * Menghapus hotelOcupancies berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteHotelOccupancies($id);

    /**
     * Mengupdate status hotelOcupancies.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelOccupanciesStatus($id, $status);
}
