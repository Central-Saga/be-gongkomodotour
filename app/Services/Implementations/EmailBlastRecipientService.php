<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\EmailBlastRecipientServiceInterface;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;


class EmailBlastRecipientService implements EmailBlastRecipientServiceInterface
{
    protected $emailblastrecipientrepository;

    const EMAILBLASTRECIPIENT_ALL_CACHE_KEY = 'emailblastrecipient.all';
    const EMAILBLASTRECIPIENT_ACTIVE_CACHE_KEY = 'emailblastrecipient.active';
    const EMAILBLASTRECIPIENT_INACTIVE_CACHE_KEY = 'emailblastrecipient.inactive';

    /**
     * Konstruktor EmailBlastRecipientervice.
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
     * Mengambil emailBlastRecipient dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLASTRECIPIENT_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus('Aktif');
        });
    }

    /**
     * Mengambil emailBlastRecipient dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveEmailBlastRecipient()
    {
        return Cache::remember(self::EMAILBLASTRECIPIENT_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->emailblastrecipientrepository->getEmailBlastRecipientByStatus('Non Aktif');
        });
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
        $result = $this->emailblastrecipientrepository->createEmailBlastRecipient($data);
        Cache::forget(self::EMAILBLASTRECIPIENT_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLASTRECIPIENT_ACTIVE_CACHE_KEY);
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
        $result = $this->emailblastrecipientrepository->updateEmailBlastRecipient($id, $data);
        Cache::forget(self::EMAILBLASTRECIPIENT_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLASTRECIPIENT_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus emailBlastRecipient berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEmailBlastRecipient($id)
    {
        $result = $this->emailblastrecipientrepository->deleteEmailBlastRecipient($id);
        Cache::forget(self::EMAILBLASTRECIPIENT_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLASTRECIPIENT_ACTIVE_CACHE_KEY);

        return $result;
    }
}
