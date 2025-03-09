<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\EmailBlastRecipientServiceInterface;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;

class EmailBlastRecipientService implements EmailBlastRecipientServiceInterface
{
    protected $emailblastrecipientrepository;

    const EMAILBLASTRECIPIENT_ALL_CACHE_KEY = 'emailblastrecipient.all';
    const EMAILBLAST_PENDING_CACHE_KEY       = 'emailblastrecipient.pending';
    const EMAILBLAST_SENT_CACHE_KEY          = 'emailblastrecipient.sent';
    const EMAILBLAST_FAILED_CACHE_KEY        = 'emailblastrecipient.failed';

    /**
     * Konstruktor EmailBlastRecipientService.
     *
     * @param EmailBlastRecipientRepositoryInterface $emailblastrecipientrepository
     */
    public function __construct(EmailBlastRecipientRepositoryInterface $emailblastrecipientrepository)
    {
        $this->emailblastrecipientrepository = $emailblastrecipientrepository;
    }

    /**
     * Mengambil semua emailBlastRecipient.
     *
     * @return mixed
     */
    public function getAllEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLASTRECIPIENT_ALL_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getAllEmailBlastRecipient();
        });
    }

    /**
     * Mengambil emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEmailBlastRecipientById($id)
    {
        return $this->emailblastrecipientrepository->getEmailBlastRecipientById($id);
    }

    /**
     * Membuat emailBlastRecipient baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEmailBlastRecipient(array $data)
    {
        $data['guard_name'] = 'web';
        if (isset($data['status'])) {
            // Menstandarisasi status dengan format Capitalized
            $data['status'] = ucfirst(strtolower($data['status']));
        }
        $result = $this->emailblastrecipientrepository->createEmailBlastRecipient($data);
        $this->clearEmailBlastRecipientCaches();
        return $result;
    }

    /**
     * Memperbarui emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEmailBlastRecipient($id, array $data)
    {
        $data['guard_name'] = 'web';
        if (isset($data['status'])) {
            $data['status'] = ucfirst(strtolower($data['status']));
        }
        $result = $this->emailblastrecipientrepository->updateEmailBlastRecipient($id, $data);
        $this->clearEmailBlastRecipientCaches($id);
        return $result;
    }

    /**
     * Menghapus emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteEmailBlastRecipient($id)
    {
        $result = $this->emailblastrecipientrepository->deleteEmailBlastRecipient($id);
        $this->clearEmailBlastRecipientCaches();
        return $result;
    }

    /**
     * Mengambil emailBlastRecipient berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEmailBlastRecipientByName($name)
    {
        return $this->emailblastrecipientrepository->getEmailBlastRecipientByName($name);
    }

    /**
     * Mengambil emailBlastRecipient berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getEmailBlastRecipientByStatus($status)
    {
        return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus($status);
    }

    /**
     * Mengambil emailBlastRecipient dengan status Pending.
     *
     * @return mixed
     */
    public function getPendingEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLAST_PENDING_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus('Pending');
        });
    }

    /**
     * Mengambil emailBlastRecipient dengan status Sent.
     *
     * @return mixed
     */
    public function getSentEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLAST_SENT_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus('Sent');
        });
    }

    /**
     * Mengambil emailBlastRecipient dengan status Failed.
     *
     * @return mixed
     */
    public function getFailedEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLAST_FAILED_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus('Failed');
        });
    }

    public function updateEmailBlastRecipientStatus($id, $status)
    {
        $emailBlastRecipient = $this->getEmailBlastRecipientById($id);

        if ($emailBlastRecipient) {
            $result = $this->emailblastrecipientrepository->updateEmailBlastRecipientStatus($id, $status);

            $this->clearEmailBlastRecipientCaches($id);

            return $result;
        }
    }

    public function clearEmailBlastRecipientCaches()
    {
        Cache::forget(self::EMAILBLASTRECIPIENT_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_PENDING_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SENT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_FAILED_CACHE_KEY);
    }
}
