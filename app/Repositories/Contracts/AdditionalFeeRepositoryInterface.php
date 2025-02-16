<?php

namespace App\Repositories\Contracts;

interface AdditionalFeeRepositoryInterface
{
    /**
     * Mengambil semua additional fees.
     *
     * @return mixed
     */
    public function getAllAdditionalFees();

    /**
     * Mengambil additional fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getAdditionalFeeById($id);

    /**
     * Mengambil additional fee berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getAdditionalFeeByName($name);

    /**
     * Mengambil additional fee berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getAdditionalFeeByStatus($status);

    /**
     * Membuat additional fee baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createAdditionalFee(array $data);

    /**
     * Memperbarui additional fee berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateAdditionalFee($id, array $data);

    /**
     * Menghapus additional fee berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteAdditionalFee($id);
}
