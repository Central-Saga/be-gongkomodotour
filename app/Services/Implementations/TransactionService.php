<?php

namespace App\Services\Implementation;

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
