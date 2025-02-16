<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\BookingServiceInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;


class BookingService implements BookingServiceInterface
{
    protected $bookingRepository;

    const BOOKING_ALL_CACHE_KEY = 'booking.all';
    const BOOKING_ACTIVE_CACHE_KEY = 'booking.active';
    const BOOKING_INACTIVE_CACHE_KEY = 'booking.inactive';

    /**
     * Konstruktor BookingService.
     *
     * @param BookingRepositoryInterface $bookingRepository
     */
    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Mengambil semua bookings.
     *
     * @return mixed
     */
    public function getAllBookings()
    {
        return Cache::remember(self::BOOKING_ALL_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getAllBookings();
        });
    }

    /**
     * Mengambil booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBookingById($id)
    {
        return $this->bookingRepository->getBookingById($id);
    }

    /**
     * Mengambil booking berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBookingByName($name)
    {
        return $this->bookingRepository->getBookingByName($name);
    }

    /**
     * Mengambil booking berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingByStatus($status)
    {
        return $this->bookingRepository->getBookingByStatus($status);
    }

    /**
     * Mengambil bookings dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveBookings()
    {
        return Cache::remember(self::BOOKING_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getBookingByStatus('Aktif');
        });
    }

    /**
     * Mengambil bookings dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveBookings()
    {
        return Cache::remember(self::BOOKING_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getBookingByStatus('Non Aktif');
        });
    }

    /**
     * Membuat booking baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBooking(array $data)
    {
        // Membuat booking baru
        $booking = $this->bookingRepository->createBooking($data);

        // Clear cache
        Cache::forget(self::BOOKING_ALL_CACHE_KEY);
        Cache::forget(self::BOOKING_ACTIVE_CACHE_KEY);

        return $booking;
    }

    /**
     * Memperbarui booking berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBooking($id, array $data)
    {

        // Memperbarui booking
        $booking = $this->bookingRepository->updateBooking($id, $data);

        // Clear cache
        Cache::forget(self::BOOKING_ALL_CACHE_KEY);
        Cache::forget(self::BOOKING_ACTIVE_CACHE_KEY);
        Cache::forget("booking_{$id}_with_roles");

        return $booking;
    }

    /**
     * Menghapus booking berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBooking($id)
    {
        // Menghapus booking
        $result = $this->bookingRepository->deleteBooking($id);

        // Clear cache
        Cache::forget(self::BOOKING_ALL_CACHE_KEY);
        Cache::forget(self::BOOKING_ACTIVE_CACHE_KEY);

        return $result;
    }
}
