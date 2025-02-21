<?php

namespace App\Services\Contracts;

interface BookingServiceInterface
{
    /**
     * Mengambil semua bookings.
     *
     * @return mixed
     */
    public function getAllBookings();

    /**
     * Mengambil booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBookingById($id);

    /**
     * Mengambil booking berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBookingByName($name);

    /**
     * Mengambil booking berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingByStatus($status);

    /**
     * Mengambil booking berdasarkan status pending.
     *
     * @return mixed
     */
    public function getBookingByStatusPending();

    /**
     * Mengambil booking berdasarkan status confirmed.
     *
     * @return mixed
     */
    public function getBookingByStatusConfirmed();

    /**
     * Mengambil booking berdasarkan status cancelled.
     *
     * @return mixed
     */
    public function getBookingByStatusCancelled();

    /**
     * Membuat booking baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBooking(array $data);

    /**
     * Memperbarui booking berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBooking($id, array $data);

    /**
     * Menghapus booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBooking($id);
}
