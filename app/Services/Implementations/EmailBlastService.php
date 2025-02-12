<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\EmailBlastServiceInterface;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;


class EmailBlastService implements EmailBlastServiceInterface
{
    protected $emailblastrepository;

    const EMAILBLAST_ALL_CACHE_KEY = 'emailblast.all';
    const EMAILBLAST_ACTIVE_CACHE_KEY = 'emailblast.active';
    const EMAILBLAST_INACTIVE_CACHE_KEY = 'emailblast.inactive';

    /**
     * Konstruktor EmailBlastervice.
     *
     * @param EmailBlastRepositoryInterface $emailblastrepository
     */
    public function __construct(EmailBlastRepositoryInterface $emailblastrepository)
    {
        $this->emailblastrepository = $emailblastrepository;
    }

    /**
     * Mengambil semua emailBlast.
     *
     * @return mixed
     */
    public function getAllEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_ALL_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getAllEmailBlast();
        });
    }

    /**
     * Mengambil emailBlast berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getEmailBlastById($id)
    {
        return $this->emailblastrepository->getEmailBlastById($id);
    }

    /**
     * Mengambil emailBlast berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getEmailBlastByName($name)
    {
        return $this->emailblastrepository->getEmailBlastByName($name);
    }

    /**
     * Mengambil emailBlast berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getEmailBlastByStatus($status)
    {
        return $this->emailblastrepository->getEmailBlastByStatus($status);
    }

    /**
     * Mengambil emailBlast dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('Aktif');
        });
    }

    /**
     * Mengambil emailBlast dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('Non Aktif');
        });
    }

    /**
     * Membuat emailBlast baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createEmailBlast(array $data)
    {
        $data['guard_name'] = 'web';
        $result = $this->emailblastrepository->createEmailBlast($data);
        Cache::forget(self::EMAILBLAST_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui emailBlast berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEmailBlast($id, array $data)
    {
        $data['guard_name'] = 'web';
        $result = $this->emailblastrepository->updateEmailBlast($id, $data);
        Cache::forget(self::EMAILBLAST_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_ACTIVE_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus emailBlast berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEmailBlast($id)
    {
        $result = $this->emailblastrepository->deleteEmailBlast($id);
        Cache::forget(self::EMAILBLAST_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_ACTIVE_CACHE_KEY);

        return $result;
    }
}
