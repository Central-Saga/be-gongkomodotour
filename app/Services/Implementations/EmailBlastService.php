<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\EmailBlastServiceInterface;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;

class EmailBlastService implements EmailBlastServiceInterface
{
    protected $emailblastrepository;

    const EMAILBLAST_ALL_CACHE_KEY        = 'emailblast.all';
    const EMAILBLAST_DRAFT_CACHE_KEY      = 'emailblast.draft';
    const EMAILBLAST_PENDING_CACHE_KEY    = 'emailblast.pending';
    const EMAILBLAST_SCHEDULED_CACHE_KEY  = 'emailblast.scheduled';
    const EMAILBLAST_FAILED_CACHE_KEY     = 'emailblast.failed';

    /**
     * Konstruktor EmailBlastService.
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
     * Mengambil emailBlast dengan status draft.
     *
     * @return mixed
     */
    public function getDraftEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_DRAFT_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('draft');
        });
    }

    /**
     * Mengambil emailBlast dengan status pending.
     *
     * @return mixed
     */
    public function getPendingEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_PENDING_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('pending');
        });
    }

    /**
     * Mengambil emailBlast dengan status scheduled.
     *
     * @return mixed
     */
    public function getScheduledEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_SCHEDULED_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('scheduled');
        });
    }

    /**
     * Mengambil emailBlast dengan status failed.
     *
     * @return mixed
     */
    public function getFailedEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_FAILED_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus('failed');
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
        Cache::forget(self::EMAILBLAST_DRAFT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_PENDING_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SCHEDULED_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_FAILED_CACHE_KEY);
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
        Cache::forget(self::EMAILBLAST_DRAFT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_PENDING_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SCHEDULED_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_FAILED_CACHE_KEY);
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
        Cache::forget(self::EMAILBLAST_DRAFT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_PENDING_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SCHEDULED_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_FAILED_CACHE_KEY);
        return $result;
    }
}