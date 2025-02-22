<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * @var Transaction
     */
    protected $model;

    /**
     * Constructor
     *
     * @param Transaction $model
     */
    public function __construct(Transaction $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua transaksi.
     *
     * @return mixed
     */
    public function getAllTransactions()
    {
        return $this->model->all();
    }

    /**
     * Mengambil transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTransactionById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Transaction with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil transaksi berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getTransactionByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Mengambil transaksi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getTransactionByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Membuat transaksi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTransaction(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create transaction: {$e->getMessage()}");
            return null;
        }
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
        $transaction = $this->findTransaction($id);
        if (!$transaction) {
            return null;
        }
        try {
            $transaction->update($data);
            return $transaction;
        } catch (\Exception $e) {
            Log::error("Failed to update transaction with ID {$id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Menghapus transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTransaction($id)
    {
        $transaction = $this->findTransaction($id);
        if (!$transaction) {
            return null;
        }
        try {
            $transaction->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete transaction with ID {$id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Helper method untuk menemukan transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findTransaction($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Transaction with ID {$id} not found.");
            return null;
        }
    }
}
