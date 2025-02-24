<?php

namespace App\Repositories\Contracts;

interface DetailTransactionRepositoryInterface
{
    /**
     * Mengambil semua detail transaksi.
     *
     * @return mixed
     */
    public function getAllDetailTransactions();

    /**
     * Mengambil detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getDetailTransactionById($id);

    /**
     * Mengambil detail transaksi berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getDetailTransactionByName($name);

    /**
     * Mengambil detail transaksi berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getDetailTransactionByStatus($status);

    /**
     * Membuat detail transaksi baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createDetailTransaction(array $data);

    /**
     * Memperbarui detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateDetailTransaction($id, array $data);

    /**
     * Menghapus detail transaksi berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteDetailTransaction($id);

    /**
     * Mengupdate detail transaksi status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateDetailTransactionStatus($id, $status);
}
