<?php

namespace App\Repositories\Eloquent;

use App\Models\EmailBlastRecipient;
use App\Repositories\Contracts\EmailBlastRecipientRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailBlastRecipientRepository implements EmailBlastRecipientRepositoryInterface
{
    public function getAllEmailBlastRecipient()
    {
        return EmailBlastRecipient::all();
    }

    public function getEmailBlastRecipientById($id)
    {
        try {
            return EmailBlastRecipient::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("EmailBlastRecipient with ID {$id} not found.");
            return null;
        }
    }

    public function createEmailBlastRecipient(array $data)
    {
        return EmailBlastRecipient::create($data);
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
        return EmailBlastRecipient::where('subject', $name)->first();
    }

    public function getEmailBlastRecipientByStatus($status)
    {
        return EmailBlastRecipient::where('status', $status)->get();
    }

    public function findEmailBlastRecipient($id)
    {
        return $this->getEmailBlastRecipientById($id);
    }
}