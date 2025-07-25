<?php

namespace App\Services\Contracts;

interface EmailBlastRecipientServiceInterface
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
     * Mengambil semua emailBlastRecipient yang aktif.
     *
     * @return mixed
     */
    public function getPendingEmailBlastRecipient();

    /**
     * Mengambil semua emailBlastRecipient yang tidak aktif.
     *
     * @return mixed
     */
    public function getSentEmailBlastRecipient();

    /**
     * Mengambil semua emailBlastRecipient yang tidak aktif.
     *
     * @return mixed
     */
    public function getFailedEmailBlastRecipient();

    /**
     * Mengupdate status emailBlastRecipient.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastRecipientStatus($id, $status);
}
