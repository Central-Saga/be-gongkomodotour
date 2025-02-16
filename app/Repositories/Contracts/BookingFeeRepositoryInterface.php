<?php

namespace App\Repositories\Contracts;

interface BookingFeeRepositoryInterface
{
    /**
     * Mengambil semua booking fees.
     *
     * @return mixed
     */
    public function getAllBookingFees();

    /**
     * Mengambil booking fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBookingFeeById($id);

    /**
     * Mengambil booking fee berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBookingFeeByName($name);

    /**
     * Mengambil booking fee berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingFeeByStatus($status);

    /**
     * Membuat booking fee baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBookingFee(array $data);

    /**
     * Memperbarui booking fee berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBookingFee($id, array $data);

    /**
     * Menghapus booking fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBookingFee($id);
}
