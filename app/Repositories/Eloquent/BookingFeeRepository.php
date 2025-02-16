<?php

namespace App\Repositories\Eloquent;

use App\Models\BookingFee;
use App\Repositories\Contracts\BookingFeeRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingFeeRepository implements BookingFeeRepositoryInterface
{
    /**
     * @var BookingFee
     */
    protected $bookingFee;

    /**
     * Konstruktor BookingFeeRepository.
     *
     * @param BookingFee $bookingFee
     */
    public function __construct(BookingFee $bookingFee)
    {
        $this->bookingFee = $bookingFee;
    }

    /**
     * Mengambil semua booking fees.
     *
     * @return mixed
     */
    public function getAllBookingFees()
    {
        return $this->bookingFee->with('additionalFee')->get();
    }

    /**
     * Mengambil booking berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBookingFeeById($id)
    {
        try {
            // Mengambil trip berdasarkan ID, handle jika tidak ditemukan
            return $this->bookingFee->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Booking with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil booking fee berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBookingFeeByName($name)
    {
        return $this->bookingFee->where('name', $name)->with('additionalFee')->first();
    }

    /**
     * Mengambil booking fee berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBookingFeeByStatus($status)
    {
        return $this->bookingFee->where('status', $status)->with('additionalFee')->get();
    }

    /**
     * Membuat booking fee baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBookingFee(array $data)
    {
        try {
            return $this->bookingFee->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create booking fee: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui booking fee berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBookingFee($id, array $data)
    {
        $bookingFee = $this->findBookingFee($id);

        if ($bookingFee) {
            try {
                $bookingFee->update($data);
                return $bookingFee;
            } catch (\Exception $e) {
                Log::error("Failed to update booking fee with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus booking fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBookingFee($id)
    {
        $bookingFee = $this->findBookingFee($id);

        if ($bookingFee) {
            try {
                $bookingFee->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete booking fee with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan booking fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findBookingFee($id)
    {
        try {
            return $this->bookingFee->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Booking fee with ID {$id} not found.");
            return null;
        }
    }
}
