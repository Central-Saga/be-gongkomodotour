<?php

namespace App\Repositories\Contracts;

interface HotelRequestRepositoryInterface
{
    /**
     * Mengambil semua permintaan hotel.
     *
     * @return mixed
     */
    public function getAllHotelRequests();

    /**
     * Mengambil permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getHotelRequestById($id);

    /**
     * Mengambil permintaan hotel berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getHotelRequestByName($name);

    /**
     * Mengambil permintaan hotel berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getHotelRequestByStatus($status);

    /**
     * Membuat permintaan hotel baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createHotelRequest(array $data);

    /**
     * Memperbarui permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateHotelRequest($id, array $data);

    /**
     * Menghapus permintaan hotel berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteHotelRequest($id);

    /**
     * Mengupdate permintaan hotel status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelRequestStatus($id, $status);
}
