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
        return $this->booking->with([
            'trip',
            'tripDuration',
            'tripDuration.tripPrices',
            'customer',
            'boat',
            'cabin',
            'user',
            'hotelOccupancy',
            'additionalFees'
        ])->get();
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
            return $this->booking->with([
                'trip',
                'tripDuration',
                'tripDuration.tripPrices',
                'customer',
                'boat',
                'cabin',
                'user',
                'hotelOccupancy',
                'additionalFees'
            ])->findOrFail($id);
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
        return $this->booking->where('name', $name)
            ->with([
                'trip',
                'tripDuration',
                'tripDuration.tripPrices',
                'customer',
                'boat',
                'cabin',
                'user',
                'hotelOccupancy',
                'additionalFees'
            ])->first();
    }

    /**
     * Mengambil booking berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingByStatus($status)
    {
        return $this->booking->with([
            'trip',
            'tripDuration',
            'tripDuration.tripPrices',
            'customer',
            'boat',
            'cabin',
            'user',
            'hotelOccupancy',
            'additionalFees'
        ])->where('status', $status)->get();
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
            // Buat booking utama
            $booking = $this->booking->create($data);

            // Jika terdapat data untuk relasi many-to-many, contohnya:
            if (isset($data['cabin_ids']) && is_array($data['cabin_ids'])) {
                // Misalnya, data hanya berupa array ID cabin
                $booking->cabin()->sync($data['cabin_ids']);
            }

            if (isset($data['boat_ids']) && is_array($data['boat_ids'])) {
                $booking->boat()->sync($data['boat_ids']);
            }

            if (isset($data['additional_fee_ids']) && is_array($data['additional_fee_ids'])) {
                // Jika data additional fee berupa array ID atau array dengan data pivot
                $additionalFeesData = [];
                foreach ($data['additional_fee_ids'] as $fee) {
                    if (is_array($fee)) {
                        // Contoh: ['additional_fee_id' => 1, 'total_price' => 100]
                        $additionalFeesData[$fee['additional_fee_id']] = ['total_price' => $fee['total_price']];
                    } else {
                        $additionalFeesData[$fee] = ['total_price' => 0];
                    }
                }
                $booking->additionalFees()->sync($additionalFeesData);
            }

            return $booking;
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

                // Perbarui relasi pivot jika data tersedia
                if (isset($data['cabin_ids']) && is_array($data['cabin_ids'])) {
                    $booking->cabin()->sync($data['cabin_ids']);
                }

                if (isset($data['boat_ids']) && is_array($data['boat_ids'])) {
                    $booking->boat()->sync($data['boat_ids']);
                }

                if (isset($data['additional_fee_ids']) && is_array($data['additional_fee_ids'])) {
                    $additionalFeesData = [];
                    foreach ($data['additional_fee_ids'] as $fee) {
                        if (is_array($fee)) {
                            $additionalFeesData[$fee['additional_fee_id']] = ['total_price' => $fee['total_price']];
                        } else {
                            $additionalFeesData[$fee] = ['total_price' => 0];
                        }
                    }
                    $booking->additionalFees()->sync($additionalFeesData);
                }

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
