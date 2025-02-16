<?php

namespace App\Services\Contracts;

interface SurchargeServiceInterface
{
    /**
     * Mengambil semua surcharges.
     *
     * @return mixed
     */
    public function getAllSurcharges();

    /**
     * Mengambil surcharge berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getSurchargeById($id);

    /**
     * Mengambil surcharge berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getSurchargeByName($name);

    /**
     * Mengambil surcharge berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getSurchargeByStatus($status);

    /**
     * Mengambil semua surcharges yang aktif.
     *
     * @return mixed
     */
    public function getActiveSurcharges();

    /**
     * Mengambil semua surcharges yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveSurcharges();

    /**
     * Membuat surcharge baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createSurcharge(array $data);

    /**
     * Memperbarui surcharge berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateSurcharge($id, array $data);

    /**
     * Menghapus surcharge berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteSurcharge($id);
}
