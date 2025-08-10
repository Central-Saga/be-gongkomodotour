<?php

namespace App\Repositories\Eloquent;

use App\Models\EmailBlastRecipient;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailBlastRecipientRepository implements EmailBlastRecipientRepositoryInterface
{
    protected $model;

    public function __construct(EmailBlastRecipient $emailBlastRecipient)
    {
        $this->model = $emailBlastRecipient;
    }

    public function getAllEmailBlastRecipient()
    {
        return $this->model->all();
    }

    public function getEmailBlastRecipientById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("EmailBlastRecipient with ID {$id} not found.");
            return null;
        }
    }

    public function createEmailBlastRecipient(array $data)
    {
        return $this->model->create($data);
    }

    public function updateEmailBlastRecipient($id, array $data)
    {
        $emailBlastRecipient = $this->getEmailBlastRecipientById($id);

        if ($emailBlastRecipient) {
            $emailBlastRecipient->update($data);
        }

        return $emailBlastRecipient;
    }

    public function deleteEmailBlastRecipient($id)
    {
        $emailBlastRecipient = $this->getEmailBlastRecipientById($id);

        if ($emailBlastRecipient) {
            $emailBlastRecipient->delete();
        }

        return $emailBlastRecipient;
    }

    public function getEmailBlastRecipientByName($name)
    {
        return $this->model->where('subject', $name)->first();
    }

    public function getEmailBlastRecipientByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    public function findEmailBlastRecipient($id)
    {
        return $this->getEmailBlastRecipientById($id);
    }

    /**
     * Mengupdate email blast recipient status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastRecipientStatus($id, $status)
    {
        $emailBlastRecipient = $this->findEmailBlastRecipient($id);

        if ($emailBlastRecipient) {
            $emailBlastRecipient->status = $status;
            $emailBlastRecipient->save();
            return $emailBlastRecipient;
        }
        return null;
    }

    /**
     * Menghapus semua emailBlastRecipient berdasarkan email_blast_id.
     *
     * @param int $emailBlastId
     * @return mixed
     */
    public function deleteEmailBlastRecipientsByEmailBlastId($emailBlastId)
    {
        try {
            return $this->model->where('email_blast_id', $emailBlastId)->delete();
        } catch (\Exception $e) {
            Log::error("Failed to delete email blast recipients: {$e->getMessage()}");
            throw $e;
        }
    }
}
