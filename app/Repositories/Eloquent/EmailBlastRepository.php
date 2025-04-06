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
        return $this->model->with('recipients')->get();
    }

    public function getEmailBlastById($id)
    {
        try {
            return $this->model->with('recipients')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("EmailBlast with ID {$id} not found.");
            return null;
        }
    }

    public function createEmailBlast(array $data)
    {
        $emailBlast = $this->model->create($data);
        $emailBlast->load('recipients');
        return $emailBlast;
    }

    public function updateEmailBlast($id, array $data)
    {
        $emailBlast = $this->getEmailBlastById($id);

        if ($emailBlast) {
            $emailBlast->update($data);
            $emailBlast->load('recipients');
        }

        return $emailBlast;
    }

    public function deleteEmailBlast($id)
    {
        $emailBlast = $this->getEmailBlastById($id);

        if ($emailBlast) {
            $emailBlast->delete();
            $emailBlast->load('recipients');
        }

        return $emailBlast;
    }

    public function getEmailBlastByName($name)
    {
        return $this->model->where('subject', $name)->with('recipients')->first();
    }

    public function getEmailBlastByStatus($status)
    {
        return $this->model->where('status', $status)->with('recipients')->get();
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
            $emailBlast->load('recipients');
            return $emailBlast;
        }
        return null;
    }
}
