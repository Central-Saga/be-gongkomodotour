<?php

namespace App\Services\Contracts;

interface CabinServiceInterface
{
    /**
     * Mengambil semua cabin.
     *
     * @return mixed
     */
    public function getAllCabin();

    /**
     * Mengambil cabin berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getCabinById($id);

    /**
     * Membuat cabin baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createCabin(array $data);

    /**
     * Memperbarui cabin berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateCabin($id, array $data);

    /**
     * Menghapus cabin berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteCabin($id);

    /**
     * Mengambil cabin berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getCabinByName($name);

    /**
     * Mengambil cabin berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getCabinByStatus($status);

    /**
     * Mengambil semua cabin yang aktif.
     *
     * @return mixed
     */
    public function getActiveCabin();

    /**
     * Mengambil semua cabin yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveCabin();

    /**
     * Mengupdate status cabin.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateCabinStatus($id, $status);
}
