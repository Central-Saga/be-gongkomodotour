<?php

namespace App\Repositories\Contracts;

interface EmailBlastRecipientRepositoryInterface
{
    /**
     * Mengambil semua emailBlastRecipient.
     *
     * @return mixed
     */
    public function getAllEmailBlastRecipient();

    /**
     * Mengambil emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEmailBlastRecipientById($id);

    /**
     * Membuat emailBlastRecipient baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEmailBlastRecipient(array $data);

    /**
     * Memperbarui emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEmailBlastRecipient($id, array $data);

    /**
     * Menghapus emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteEmailBlastRecipient($id);

    /**
     * Mengambil emailBlastRecipient berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEmailBlastRecipientByName($name);

    /**
     * Mengambil emailBlastRecipient berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getEmailBlastRecipientByStatus($status);

    /**
     * Mencari emailBlastRecipient berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findEmailBlastRecipient($id);

    /**
     * Mengupdate emailBlastRecipient status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastRecipientStatus($id, $status);

    /**
     * Menghapus semua emailBlastRecipient berdasarkan email_blast_id.
     *
     * @param int $emailBlastId
     * @return mixed
     */
    public function deleteEmailBlastRecipientsByEmailBlastId($emailBlastId);
}
