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
     * Mengambil semua bookings yang aktif.
     *
     * @return mixed
     */
    public function getActiveBookings();

    /**
     * Mengambil semua bookings yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveBookings();

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
