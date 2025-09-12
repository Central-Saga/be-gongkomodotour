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
     * Mengambil additional fees berdasarkan trip id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function getAdditionalFeesByTripId($trip_id);

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

    /**
     * Menghapus additional fees yang tidak terdapat dalam trip.
     *
     * @param int $trip_id
     * @param array $existing_id
     * @return mixed
     */
    public function deleteAdditionalFeesNotIn($trip_id, $existing_id);

    /**
     * Menghapus semua additional fees berdasarkan trip_id.
     *
     * @param int $trip_id
     * @return mixed
     */
    public function deleteAdditionalFeesByTripId($trip_id);

    /**
     * Mengupdate additional fee status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateAdditionalFeeStatus($id, $status);
}
