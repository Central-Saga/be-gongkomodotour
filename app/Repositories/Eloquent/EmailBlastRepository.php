<?php

namespace App\Repositories\Eloquent;

use App\Models\EmailBlast;
use App\Repositories\Contracts\EmailBlastRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmailBlastRepository implements EmailBlastRepositoryInterface
{
    protected $model;

    public function __construct(EmailBlast $emailBlast)
    {
        $this->model = $emailBlast;
    }

    public function getAllEmailBlast()
    {
        return $this->model->all();
    }

    public function getEmailBlastById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("EmailBlast with ID {$id} not found.");
            return null;
        }
    }

    public function createEmailBlast(array $data)
    {
        return $this->model->create($data);
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
        return $this->model->where('subject', $name)->first();
    }

    public function getEmailBlastByStatus($status)
    {
        return $this->model->where('status', $status)->get();
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
