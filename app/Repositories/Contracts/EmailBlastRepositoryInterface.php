<?php

namespace App\Repositories\Contracts;

interface EmailBlastRepositoryInterface
{
    /**
     * Mengambil semua emailBlast.
     *
     * @return mixed
     */
    public function getAllEmailBlast();

    /**
     * Mengambil emailBlast berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEmailBlastById($id);

    /**
     * Membuat emailBlast baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEmailBlast(array $data);

    /**
     * Memperbarui emailBlast berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEmailBlast($id, array $data);

    /**
     * Menghapus emailBlast berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteEmailBlast($id);

    /**
     * Mengambil emailBlast berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEmailBlastByName($name);

    /**
     * Mengambil emailBlast berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getEmailBlastByStatus($status);

    /**
     * Mencari emailBlast berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findEmailBlast($id);

    /**
     * Mengupdate emailBlast status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastStatus($id, $status);
}
