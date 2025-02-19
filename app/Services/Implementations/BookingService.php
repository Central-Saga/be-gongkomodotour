<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\BookingServiceInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\AdditionalFeeRepositoryInterface;
use App\Repositories\Contracts\BookingFeeRepositoryInterface;

class BookingService implements BookingServiceInterface
{
    protected $bookingRepository;
    protected $additionalFeeRepository;
    protected $bookingFeeRepository;

    const BOOKING_ALL_CACHE_KEY = 'booking.all';
    const BOOKING_ACTIVE_CACHE_KEY = 'booking.active';
    const BOOKING_INACTIVE_CACHE_KEY = 'booking.inactive';
    const BOOKING_PENDING_CACHE_KEY = 'booking.pending';
    const BOOKING_CONFIRMED_CACHE_KEY = 'booking.confirmed';
    const BOOKING_CANCELLED_CACHE_KEY = 'booking.cancelled';

    /**
     * Konstruktor BookingService.
     *
     * @param BookingRepositoryInterface $bookingRepository
     * @param AdditionalFeeRepositoryInterface $additionalFeeRepository
     * @param BookingFeeRepositoryInterface $bookingFeeRepository
     */
    public function __construct(
        BookingRepositoryInterface $bookingRepository,
        AdditionalFeeRepositoryInterface $additionalFeeRepository,
        BookingFeeRepositoryInterface $bookingFeeRepository
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->additionalFeeRepository = $additionalFeeRepository;
        $this->bookingFeeRepository = $bookingFeeRepository;
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
     * Mengambil booking berdasarkan status pending.
     *
     * @return mixed
     */
    public function getBookingByStatusPending()
    {
        return Cache::remember(self::BOOKING_PENDING_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getBookingByStatus('Pending');
        });
    }

    /**
     * Mengambil booking berdasarkan status confirmed.
     *
     * @return mixed
     */
    public function getBookingByStatusConfirmed()
    {
        return Cache::remember(self::BOOKING_CONFIRMED_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getBookingByStatus('Confirmed');
        });
    }

    /**
     * Mengambil booking berdasarkan status cancelled.
     *
     * @return mixed
     */
    public function getBookingByStatusCancelled()
    {
        return Cache::remember(self::BOOKING_CANCELLED_CACHE_KEY, 3600, function () {
            return $this->bookingRepository->getBookingByStatus('Cancelled');
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
        // Jika perlu, lakukan mapping data seperti memindahkan booking_fees ke optional_additional_fees
        if (isset($data['booking_fees'])) {
            $data['optional_additional_fees'] = array_map(function ($fee) {
                return $fee['additional_fee_id'];
            }, $data['booking_fees']);
            unset($data['booking_fees']);
        }

        $booking = $this->bookingRepository->createBooking($data);

        if ($booking) {
            // Setelah booking dibuat, buat atau perbarui booking fee
            $this->createOrUpdateBookingFees($booking, $data);
            // Clear cache terkait
            $this->clearBookingCaches();
            $booking->refresh();
        }

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
        // Jika perlu, lakukan mapping data seperti memindahkan booking_fees ke optional_additional_fees
        if (isset($data['booking_fees'])) {
            $data['optional_additional_fees'] = array_map(function ($fee) {
                return $fee['additional_fee_id'];
            }, $data['booking_fees']);
            unset($data['booking_fees']);
        }

        $booking = $this->bookingRepository->updateBooking($id, $data);

        if ($booking) {
            // Jika ada perubahan yang memengaruhi fee, regenerasi booking fee
            $this->createOrUpdateBookingFees($booking, $data);

            $this->clearBookingCaches();
            $booking->refresh();
        }

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
        $result = $this->bookingRepository->deleteBooking($id);

        $this->clearBookingCaches();
        return $result;
    }

    /**
     * Membuat atau memperbarui booking fee berdasarkan additional fee.
     * @param mixed $booking
     */
    protected function createOrUpdateBookingFees($booking, array $data)
    {
        // Proses fee wajib (is_required true)
        if ($booking->trip_id) {
            $cacheKey = 'additional_fee_trip_' . $booking->trip_id;
            $additionalFees = Cache::remember($cacheKey, 3600, function () use ($booking) {
                return $this->additionalFeeRepository->getAdditionalFeesByTripId($booking->trip_id);
            });
            if ($additionalFees) {
                foreach ($additionalFees as $fee) {
                    if ($fee->is_required && $fee->status === 'Aktif') {
                        $existingBookingFee = $booking->bookingFees()
                            ->where('additional_fee_id', $fee->id)
                            ->first();

                        $dataFee = [
                            'total_price' => $fee->price,
                        ];

                        if ($existingBookingFee) {
                            $this->bookingFeeRepository->updateBookingFee($existingBookingFee->id, $dataFee);
                        } else {
                            $dataFee['booking_id'] = $booking->id;
                            $dataFee['additional_fee_id'] = $fee->id;
                            $this->bookingFeeRepository->createBookingFee($dataFee);
                        }
                    }
                }
            }
        }

        // Proses fee optional, jika ada pada input (telah dipetakan ke optional_additional_fees)
        if (isset($data['optional_additional_fees']) && is_array($data['optional_additional_fees'])) {
            foreach ($data['optional_additional_fees'] as $feeId) {
                $fee = $this->additionalFeeRepository->getAdditionalFeeById($feeId);
                if ($fee && !$fee->is_required && $fee->status === 'Aktif') {
                    $existingBookingFee = $booking->bookingFees()
                        ->where('additional_fee_id', $fee->id)
                        ->first();

                    $dataFee = [
                        'total_price' => $fee->price,
                    ];

                    if ($existingBookingFee) {
                        $this->bookingFeeRepository->updateBookingFee($existingBookingFee->id, $dataFee);
                    } else {
                        $dataFee['booking_id'] = $booking->id;
                        $dataFee['additional_fee_id'] = $fee->id;
                        $this->bookingFeeRepository->createBookingFee($dataFee);
                    }
                }
            }
        }
    }

    /**
     * Menghapus semua cache booking
     *
     * @return void
     */
    public function clearBookingCaches()
    {
        Cache::forget(self::BOOKING_ALL_CACHE_KEY);
        Cache::forget(self::BOOKING_ACTIVE_CACHE_KEY);
        Cache::forget(self::BOOKING_PENDING_CACHE_KEY);
        Cache::forget(self::BOOKING_CONFIRMED_CACHE_KEY);
        Cache::forget(self::BOOKING_CANCELLED_CACHE_KEY);
    }
}
