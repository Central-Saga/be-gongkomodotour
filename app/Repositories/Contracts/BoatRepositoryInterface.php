<?php

namespace App\Repositories\Contracts;

interface BoatRepositoryInterface
{
    /**
     * Mengambil semua boat.
     *
     * @return mixed
     */
    public function getAllBoat();

    /**
     * Mengambil boat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBoatById($id);

    /**
     * Membuat boat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBoat(array $data);

    /**
     * Memperbarui boat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBoat($id, array $data);

    /**
     * Menghapus boat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBoat($id);

    /**
     * Mengambil boat berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBoatByName($name);

    /**
     * Mengambil boat berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBoatByStatus($status);

    /**
     * Mencari boat berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findBoat($id);
}