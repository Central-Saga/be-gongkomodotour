<?php

namespace App\Services\Implementations;

use App\Models\Booking;
use App\Models\Surcharge;
use App\Models\HotelRequest;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TransactionServiceInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionService implements TransactionServiceInterface
{
    /**
     * @var TransactionRepositoryInterface
     */
    protected $repository;

    const TRANSACTIONS_ALL_CACHE_KEY = 'transactions.all';
    const TRANSACTIONS_WAITING_CACHE_KEY = 'transactions.waiting';
    const TRANSACTIONS_PAID_CACHE_KEY = 'transactions.paid';
    const TRANSACTIONS_REJECTED_CACHE_KEY = 'transactions.rejected';

    /**
     * @param TransactionRepositoryInterface $repository
     */
    public function __construct(TransactionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua transaksi.
     *
     * @return mixed
     */
    public function getAllTransactions()
    {
        return Cache::remember(self::TRANSACTIONS_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllTransactions();
        });
    }

    /**
     * Mengambil transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTransactionById($id)
    {
        return $this->repository->getTransactionById($id);
    }

    /**
     * Mengambil transaksi berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTransactionByName($name)
    {
        return $this->repository->getTransactionByName($name);
    }

    /**
     * Mengambil transaksi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTransactionByStatus($status)
    {
        return $this->repository->getTransactionByStatus($status);
    }

    /**
     * Mengambil semua transaksi yang Menunggu Pembayaran.
     *
     * @return mixed
     */
    public function getWaitingTransactions()
    {
        return Cache::remember(self::TRANSACTIONS_WAITING_CACHE_KEY, 3600, function () {
            return $this->repository->getTransactionByStatus('Menunggu Pembayaran');
        });
    }

    /**
     * Mengambil semua transaksi yang Lunas.
     *
     * @return mixed
     */
    public function getPaidTransactions()
    {
        return Cache::remember(self::TRANSACTIONS_PAID_CACHE_KEY, 3600, function () {
            return $this->repository->getTransactionByStatus('Lunas');
        });
    }

    /**
     * Mengambil semua transaksi yang Ditolak.
     *
     * @return mixed
     */
    public function getRejectedTransactions()
    {
        return Cache::remember(self::TRANSACTIONS_REJECTED_CACHE_KEY, 3600, function () {
            return $this->repository->getTransactionByStatus('Ditolak');
        });
    }

    /**
     * Membuat transaksi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTransaction(array $data)
    {
        $transaction = $this->repository->createTransaction($data);
        if ($transaction) {
            // Membuat detail transaksi untuk Hotel Request jika data tersedia
            if (isset($data['hotel_request_details']) && is_array($data['hotel_request_details'])) {
                foreach ($data['hotel_request_details'] as $detail) {
                    // Jika hotel_request_id tidak ada pada payload, maka buat HotelRequest baru
                    if (!isset($detail['hotel_request_id'])) {
                        $hotelRequest = HotelRequest::create([
                            'transaction_id'       => $transaction->id,
                            'user_id'              => auth()->id(), // sesuaikan dengan logika otentikasi yang digunakan
                            'confirmed_note'       => $detail['confirmed_note'] ?? '',
                            'requested_hotel_name' => $detail['requested_hotel_name'] ?? '',
                            'request_status'       => 'Menunggu Konfirmasi',
                            'confirmed_price'      => $detail['confirmed_price'] ?? 0,
                        ]);
                        // Tetapkan id hotel_request yang baru saja dibuat ke detail
                        $detail['hotel_request_id'] = $hotelRequest->id;
                    }

                    $transaction->details()->create([
                        'amount'         => $detail['amount'] ?? 0,
                        'description'    => $detail['description'] ?? 'Payment for Hotel Request',
                        'reference_id'   => $detail['hotel_request_id'],
                        'reference_type' => \App\Models\HotelRequest::class,
                        'type'           => 'Additional Fee'
                    ]);
                }
            }

            // Pengecekan otomatis surcharge berdasarkan tanggal booking
            $booking = Booking::find($data['booking_id']);
            if ($booking) {
                $matchingSurcharge = Surcharge::where('start_date', $booking->start_date)
                    ->where('end_date', $booking->end_date)
                    ->first();

                if ($matchingSurcharge) {
                    $transaction->details()->create([
                        'amount'         => $matchingSurcharge->amount,
                        'description'    => 'Automatically added surcharge based on booking dates',
                        'reference_id'   => $matchingSurcharge->id,
                        'reference_type' => \App\Models\Surcharge::class,
                        'type'           => 'Surcharge'
                    ]);
                }
            }

            // Jika masih ada data surcharge_details yang dikirim secara manual, proses juga
            if (isset($data['surcharge_details']) && is_array($data['surcharge_details'])) {
                foreach ($data['surcharge_details'] as $detail) {
                    $transaction->details()->create([
                        'amount'         => $detail['amount'] ?? 0,
                        'description'    => $detail['description'] ?? 'Payment for Surcharge',
                        'reference_id'   => $detail['surcharge_id'],
                        'reference_type' => \App\Models\Surcharge::class,
                        'type'           => 'Surcharge'
                    ]);
                }
            }

            $this->clearTransactionCaches();
            return true;
        }
        return false;
    }

    /**
     * Memperbarui transaksi berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTransaction($id, array $data)
    {
        $transaction = $this->repository->getTransactionById($id);
        if ($transaction) {
            $this->repository->updateTransaction($id, $data);

            // Sinkronisasi detail transaksi: hapus semua detail lama terlebih dahulu
            $transaction->details()->delete();

            // Buat kembali detail transaksi untuk Hotel Request jika data tersedia
            if (isset($data['hotel_request_details']) && is_array($data['hotel_request_details'])) {
                foreach ($data['hotel_request_details'] as $detail) {
                    $transaction->details()->create([
                        'amount' => $detail['amount'] ?? 0,
                        'description' => $detail['description'] ?? 'Payment for Hotel Request',
                        'reference_id' => $detail['hotel_request_id'],
                        'reference_type' => \App\Models\HotelRequest::class,
                        'type' => 'Additional Fee'
                    ]);
                }
            }

            // Pengecekan otomatis surcharge berdasarkan tanggal booking
            $booking = Booking::find($data['booking_id']);
            if ($booking) {
                $matchingSurcharge = Surcharge::where('start_date', $booking->start_date)
                    ->where('end_date', $booking->end_date)
                    ->first();

                if ($matchingSurcharge) {
                    $transaction->details()->create([
                        'amount' => $matchingSurcharge->amount,
                        'description' => 'Automatically added surcharge based on booking dates',
                        'reference_id' => $matchingSurcharge->id,
                        'reference_type' => \App\Models\Surcharge::class,
                        'type' => 'Surcharge'
                    ]);
                }
            }

            // Jika masih ada data surcharge_details yang dikirim secara manual, proses juga
            if (isset($data['surcharge_details']) && is_array($data['surcharge_details'])) {
                foreach ($data['surcharge_details'] as $detail) {
                    $transaction->details()->create([
                        'amount' => $detail['amount'] ?? 0,
                        'description' => $detail['description'] ?? 'Payment for Surcharge',
                        'reference_id' => $detail['surcharge_id'],
                        'reference_type' => \App\Models\Surcharge::class,
                        'type' => 'Surcharge'
                    ]);
                }
            }

            $this->clearTransactionCaches();
            return true;
        }
        return false;
    }

    /**
     * Menghapus transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTransaction($id)
    {
        $transaction = $this->repository->getTransactionById($id);
        if ($transaction) {
            $this->repository->deleteTransaction($id);
            $this->clearTransactionCaches();
            return true;
        }
        return false;
    }

    /**
     * Menghapus semua cache transaksi
     *
     * @param int|null $id
     * @return void
     */
    public function clearTransactionCaches()
    {
        Cache::forget(self::TRANSACTIONS_ALL_CACHE_KEY);
        Cache::forget(self::TRANSACTIONS_WAITING_CACHE_KEY);
        Cache::forget(self::TRANSACTIONS_PAID_CACHE_KEY);
        Cache::forget(self::TRANSACTIONS_REJECTED_CACHE_KEY);
    }
}
