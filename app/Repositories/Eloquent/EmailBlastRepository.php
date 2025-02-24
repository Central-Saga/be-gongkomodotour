<?php

namespace App\Repositories\Eloquent;

use App\Models\EmailBlast;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailBlastRepository implements EmailBlastRepositoryInterface
{
    public function getAllEmailBlast()
    {
        return EmailBlast::all();
    }

    public function getEmailBlastById($id)
    {
        try {
            return EmailBlast::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("EmailBlast with ID {$id} not found.");
            return null;
        }
    }

    public function createEmailBlast(array $data)
    {
        return EmailBlast::create($data);
    }

    public function updateEmailBlast($id, array $data)
    {
        $emailBlast = $this->getEmailBlastById($id);

        if ($emailBlast) {
            $emailBlast->update($data);
        }

        return $emailBlast;
    }

    public function deleteEmailBlast($id)
    {
        $emailBlast = $this->getEmailBlastById($id);

        if ($emailBlast) {
            $emailBlast->delete();
        }

        return $emailBlast;
    }

    public function getEmailBlastByName($name)
    {
        return EmailBlast::where('subject', $name)->first();
    }

    public function getEmailBlastByStatus($status)
    {
        return EmailBlast::where('status', $status)->get();
    }

    public function findEmailBlast($id)
    {
        return $this->getEmailBlastById($id);
    }

    /**
     * Mengupdate email blast status.
     *
     * @param int $id
     * @param string $status
     * @return mixed
     */
    public function updateEmailBlastStatus($id, $status)
    {
        $emailBlast = $this->findEmailBlast($id);

        if ($emailBlast) {
            $emailBlast->status = $status;
            $emailBlast->save();
            return $emailBlast;
        }
        return null;
    }
}
