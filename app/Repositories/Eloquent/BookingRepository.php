<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingRepository implements BookingRepositoryInterface
{
    /**
     * @var Booking
     */
    protected $booking;

    /**
     * Konstruktor BookingRepository.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Mengambil semua bookings.
     *
     * @return mixed
     */
    public function getAllBookings()
    {
        return $this->booking->with('trip', 'tripDuration', 'tripDuration.tripPrices', 'customer', 'boat', 'cabin', 'user', 'hotelOccupancy', 'bookingFees', 'bookingFees.additionalFee')->get();
    }

    /**
     * Mengambil booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBookingById($id)
    {
        try {
            // Mengambil trip berdasarkan ID, handle jika tidak ditemukan
            return $this->booking->with('trip', 'tripDuration', 'tripDuration.tripPrices', 'customer', 'boat', 'cabin', 'user', 'hotelOccupancy', 'bookingFees', 'bookingFees.additionalFee')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Booking with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil booking berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBookingByName($name)
    {
        return $this->booking->where('name', $name)->with('trip', 'tripDuration', 'tripDuration.tripPrices', 'customer', 'boat', 'cabin', 'user', 'hotelOccupancy', 'bookingFees', 'bookingFees.additionalFee')->first();
    }

    /**
     * Mengambil booking berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingByStatus($status)
    {
        return $this->booking->with('trip', 'tripDuration', 'tripDuration.tripPrices', 'customer', 'boat', 'cabin', 'user', 'hotelOccupancy', 'bookingFees', 'bookingFees.additionalFee')->where('status', $status)->get();
    }

    /**
     * Membuat booking baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBooking(array $data)
    {
        try {
            return $this->booking->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create booking: {$e->getMessage()}");
            return null;
        }
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
        $booking = $this->findBooking($id);

        if ($booking) {
            try {
                $booking->update($data);
                return $booking;
            } catch (\Exception $e) {
                Log::error("Failed to update booking with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBooking($id)
    {
        $booking = $this->findBooking($id);

        if ($booking) {
            try {
                $booking->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete booking with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findBooking($id)
    {
        try {
            return $this->booking->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Booking with ID {$id} not found.");
            return null;
        }
    }
}
