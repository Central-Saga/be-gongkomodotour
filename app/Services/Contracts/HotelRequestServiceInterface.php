<?php

namespace App\Services\Contracts;

interface HotelRequestServiceInterface
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
     * Mengambil permintaan hotel berdasarkan status Menunggu Konfirmasi.
     *
     * @return mixed
     */
    public function getHotelRequestByStatusWaitingConfirmation();

    /**
     * Mengambil permintaan hotel berdasarkan status Diterima.
     *
     * @return mixed
     */
    public function getHotelRequestByStatusAccepted();

    /**
     * Mengambil permintaan hotel berdasarkan status Ditolak.
     *
     * @return mixed
     */
    public function getHotelRequestByStatusRejected();

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
     * Mengupdate status permintaan hotel.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateHotelRequestStatus($id, $status);
}
