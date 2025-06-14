<?php

namespace App\Services\Contracts;

interface EmailBlastServiceInterface
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
     * Mengambil semua emailBlast yang aktif.
     *
     * @return mixed
     */
    public function getDraftEmailBlast();

    /**
     * Mengambil emailBlast dengan status pending.
     *
     * @return mixed
     */
    public function getSentEmailBlast();

    /**
     * Mengambil emailBlast dengan status scheduled.
     *
     * @return mixed
     */
    public function getScheduledEmailBlast();

    /**
     * Mengambil emailBlast dengan status failed.
     *
     * @return mixed
     */
    public function getFailedEmailBlast();

    /**
     * Mengupdate status emailBlast.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastStatus($id, $status);

    /**
     * Mengirim email blast.
     *
     * @param int $id
     * @param int|null $delayInMinutes
     * @return mixed
     */
    public function sendEmailBlast($id, $delayInMinutes = null);

    /**
     * Menjadwalkan pengiriman email blast.
     *
     * @param int $id
     * @param string $scheduledAt
     * @param int|null $delayInMinutes
     * @return mixed
     */
    public function scheduleEmailBlast($id, $scheduledAt, $delayInMinutes = null);
}
