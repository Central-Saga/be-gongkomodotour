<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Contracts\EmailBlastServiceInterface;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;
use App\Jobs\SendEmailBlastJob;

class EmailBlastService implements EmailBlastServiceInterface
{
    protected $emailblastrepository;
    protected $emailblastrecipientrepository;

    const EMAILBLAST_ALL_CACHE_KEY        = 'emailblast.all';
    const EMAILBLAST_DRAFT_CACHE_KEY      = 'emailblast.draft';
    const EMAILBLAST_SENT_CACHE_KEY       = 'emailblast.sent';
    const EMAILBLAST_SCHEDULED_CACHE_KEY  = 'emailblast.scheduled';
    const EMAILBLAST_FAILED_CACHE_KEY     = 'emailblast.failed';

    /**
     * Konstruktor EmailBlastService.
     *
     * @param EmailBlastRepositoryInterface $emailblastrepository
     * @param EmailBlastRecipientRepositoryInterface $emailblastrecipientrepository
     */
    public function __construct(
        EmailBlastRepositoryInterface $emailblastrepository,
        EmailBlastRecipientRepositoryInterface $emailblastrecipientrepository
    ) {
        $this->emailblastrepository = $emailblastrepository;
        $this->emailblastrecipientrepository = $emailblastrecipientrepository;
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
     * Mengambil emailBlast dengan status sent.
     *
     * @return mixed
     */
    public function getSentEmailBlast()
    {
        return Cache::remember(self::EMAILBLAST_SENT_CACHE_KEY, 3600, function () {
            return $this->emailblastrepository->getEmailBlastByStatus(status: 'sent');
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
     * Membuat emailBlast baru beserta penerimanya.
     *
     * @param array $data
     * @return mixed
     */
    public function createEmailBlast(array $data)
    {
        try {
            DB::beginTransaction();

            // Buat email blast utama
            $emailBlastData = [
                'subject' => $data['subject'],
                'body' => $data['body'],
                'status' => $data['status'] ?? 'Draft',
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'recipient_type' => $data['recipient_type'],
            ];

            $emailBlast = $this->emailblastrepository->createEmailBlast($emailBlastData);

            // Buat penerima email jika ada
            if (isset($data['recipients']) && is_array($data['recipients'])) {
                foreach ($data['recipients'] as $recipient) {
                    $recipientData = [
                        'email_blast_id' => $emailBlast->id,
                        'recipient_email' => $recipient['email'],
                        'status' => $recipient['status'] ?? 'Aktif',
                    ];
                    $this->emailblastrecipientrepository->createEmailBlastRecipient($recipientData);
                }
            }

            DB::commit();
            $this->clearEmailBlastCaches();

            return $emailBlast->fresh(['recipients']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create email blast: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Memperbarui emailBlast berdasarkan ID beserta penerimanya.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateEmailBlast($id, array $data)
    {
        try {
            DB::beginTransaction();

            // Update data email blast utama
            $emailBlastData = [
                'subject' => $data['subject'] ?? null,
                'body' => $data['body'] ?? null,
                'status' => $data['status'] ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? null,
                'recipient_type' => $data['recipient_type'] ?? null,
            ];

            $emailBlast = $this->emailblastrepository->updateEmailBlast($id, $emailBlastData);

            // Update penerima email jika ada
            if (isset($data['recipients']) && is_array($data['recipients'])) {
                // Hapus penerima yang tidak ada di data baru
                $existingRecipients = $emailBlast->recipients()->pluck('id')->toArray();
                $newRecipientIds = [];

                foreach ($data['recipients'] as $recipient) {
                    $recipientData = [
                        'email_blast_id' => $emailBlast->id,
                        'recipient_email' => $recipient['email'],
                        'status' => $recipient['status'] ?? 'Aktif',
                    ];

                    if (isset($recipient['id'])) {
                        $this->emailblastrecipientrepository->updateEmailBlastRecipient($recipient['id'], $recipientData);
                        $newRecipientIds[] = $recipient['id'];
                    } else {
                        $newRecipient = $this->emailblastrecipientrepository->createEmailBlastRecipient($recipientData);
                        $newRecipientIds[] = $newRecipient->id;
                    }
                }

                // Hapus penerima yang tidak ada di data baru
                $recipientsToDelete = array_diff($existingRecipients, $newRecipientIds);
                foreach ($recipientsToDelete as $recipientId) {
                    $this->emailblastrecipientrepository->deleteEmailBlastRecipient($recipientId);
                }
            }

            DB::commit();
            $this->clearEmailBlastCaches();

            return $emailBlast->fresh(['recipients']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to update email blast: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Menghapus emailBlast berdasarkan ID beserta penerimanya.
     *
     * @param int $id
     * @return bool
     */
    public function deleteEmailBlast($id)
    {
        try {
            DB::beginTransaction();

            // Hapus semua penerima email terlebih dahulu
            $this->emailblastrecipientrepository->deleteEmailBlastRecipientsByEmailBlastId($id);

            // Hapus email blast
            $result = $this->emailblastrepository->deleteEmailBlast($id);

            DB::commit();
            $this->clearEmailBlastCaches();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to delete email blast: {$e->getMessage()}");
            throw $e;
        }
    }

    public function updateEmailBlastStatus($id, $status)
    {
        $emailBlast = $this->getEmailBlastById($id);

        if ($emailBlast) {
            $result = $this->emailblastrepository->updateEmailBlastStatus($id, $status);

            $this->clearEmailBlastCaches($id);

            return $result;
        }

        return null;
    }

    public function clearEmailBlastCaches()
    {
        Cache::forget(self::EMAILBLAST_ALL_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_DRAFT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SENT_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_SCHEDULED_CACHE_KEY);
        Cache::forget(self::EMAILBLAST_FAILED_CACHE_KEY);
    }

    /**
     * Mengirim email blast.
     *
     * @param int $id
     * @param int|null $delayInMinutes
     * @return mixed
     */
    public function sendEmailBlast($id, $delayInMinutes = null)
    {
        try {
            $emailBlast = $this->getEmailBlastById($id);

            if (!$emailBlast) {
                throw new \Exception("Email blast not found");
            }

            if ($emailBlast->status !== 'Draft' && $emailBlast->status !== 'Scheduled') {
                throw new \Exception("Email blast can only be sent from Draft or Scheduled status");
            }

            DB::beginTransaction();

            // Update status menjadi Processing
            $emailBlast->update(['status' => 'Processing']);

            // Dispatch jobs untuk setiap recipient dengan delay
            foreach ($emailBlast->recipients as $recipient) {
                SendEmailBlastJob::dispatch($emailBlast, $recipient, $delayInMinutes);
            }

            DB::commit();
            $this->clearEmailBlastCaches();

            return $emailBlast;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to send email blast: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Menjadwalkan pengiriman email blast.
     *
     * @param int $id
     * @param string $scheduledAt
     * @param int|null $delayInMinutes
     * @return mixed
     */
    public function scheduleEmailBlast($id, $scheduledAt, $delayInMinutes = null)
    {
        try {
            $emailBlast = $this->getEmailBlastById($id);

            if (!$emailBlast) {
                throw new \Exception("Email blast not found");
            }

            if ($emailBlast->status !== 'Draft') {
                throw new \Exception("Email blast can only be scheduled from Draft status");
            }

            $emailBlast->update([
                'status' => 'Scheduled',
                'scheduled_at' => $scheduledAt
            ]);

            // Jika ada delay, dispatch jobs sekarang dengan delay
            if ($delayInMinutes !== null) {
                foreach ($emailBlast->recipients as $recipient) {
                    SendEmailBlastJob::dispatch($emailBlast, $recipient, $delayInMinutes);
                }
            }

            $this->clearEmailBlastCaches();

            return $emailBlast;
        } catch (\Exception $e) {
            Log::error("Failed to schedule email blast: {$e->getMessage()}");
            throw $e;
        }
    }
}
