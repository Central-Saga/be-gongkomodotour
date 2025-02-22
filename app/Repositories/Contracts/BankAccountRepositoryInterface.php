<?php

namespace App\Repositories\Contracts;

interface BankAccountRepositoryInterface
{
    /**
     * Mengambil semua data bank account.
     *
     * @return mixed
     */
    public function getAllBankAccounts();

    /**
     * Mengambil data bank account berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBankAccountById($id);

    /**
     * Mengambil data bank account berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBankAccountByName($name);

    /**
     * Mengambil data bank account berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBankAccountByStatus($status);

    /**
     * Membuat data bank account baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBankAccount(array $data);

    /**
     * Memperbarui data bank account berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBankAccount($id, array $data);

    /**
     * Menghapus data bank account berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBankAccount($id);
}
