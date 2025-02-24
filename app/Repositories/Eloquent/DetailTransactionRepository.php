<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\DetailTransactionRepositoryInterface;

class DetailTransactionRepository implements DetailTransactionRepositoryInterface
{
    /**
     * @var DetailTransaction
     */
    protected $model;

    /**
     * Constructor
     *
     * @param DetailTransaction $model
     */
    public function __construct(DetailTransaction $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua detail transaksi.
     *
     * @return mixed
     */
    public function getAllDetailTransactions()
    {
        return $this->model->all();
    }

    /**
     * Mengambil detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getDetailTransactionById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Detail transaction with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil detail transaksi berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getDetailTransactionByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * Mengambil detail transaksi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getDetailTransactionByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Membuat detail transaksi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createDetailTransaction(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            throw new \Exception('Failed to create detail transaction: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateDetailTransaction($id, array $data)
    {
        $detailTransaction = $this->getDetailTransactionById($id);
        if (!$detailTransaction) {
            return null;
        }
        try {
            $detailTransaction->update($data);
            return $detailTransaction;
        } catch (\Exception $e) {
            Log::error("Failed to update detail transaction with ID {$id}: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Menghapus detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteDetailTransaction($id)
    {
        $detailTransaction = $this->getDetailTransactionById($id);
        if (!$detailTransaction) {
            return null;
        }
        try {
            $detailTransaction->delete();
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to delete detail transaction with ID {$id}: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * Helper method untuk menemukan detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    protected function findDetailTransaction($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Detail transaction with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate detail transaksi status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateDetailTransactionStatus($id, $status)
    {
        $detailTransaction = $this->findDetailTransaction($id);

        if ($detailTransaction) {
            $detailTransaction->status = $status;
            $detailTransaction->save();
            return $detailTransaction;
        }
        return null;
    }
}
