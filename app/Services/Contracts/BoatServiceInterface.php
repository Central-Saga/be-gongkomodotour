<?php

namespace App\Services\Contracts;

interface BoatServiceInterface
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
     * Mengambil semua boat yang aktif.
     *
     * @return mixed
     */
    public function getActiveBoat();

    /**
     * Mengambil semua boat yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveBoat();

    /**
     * Mengupdate status boat.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateBoatStatus($id, $status);

    /**
     * Mengambil boat dengan trips yang terkait.
     *
     * @param int $id
     * @return mixed
     */
    public function getBoatWithTrips($id);
}
