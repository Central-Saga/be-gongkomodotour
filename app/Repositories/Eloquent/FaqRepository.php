<?php

namespace App\Repositories\Eloquent;

use App\Models\Faq;
use App\Repositories\Contracts\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FaqRepository implements FaqRepositoryInterface
{
    protected $model;

    public function __construct(Faq $faq)
    {
        $this->model = $faq;
    }

    /**
     * Mengambil semua FAQ.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllFaq()
    {
        return $this->model->all();
    }

    /**
     * Mengambil FAQ berdasarkan ID.
     *
     * @param int $id
     * @return \App\Models\Faq
     */
    public function getFaqById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Membuat FAQ baru.
     *
     * @param array $data
     * @return \App\Models\Faq
     */
    public function createFaq(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui FAQ berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Faq|null
     */
    public function updateFaq($id, array $data)
    {
        $faq = $this->findFaq($id);
        if ($faq) {
            $faq->update($data);
        }
        return $faq;
    }

    /**
     * Menghapus FAQ berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteFaq($id)
    {
        $faq = $this->findFaq($id);
        if ($faq) {
            return $faq->delete();
        }
        return false;
    }

    /**
     * Mengambil FAQ berdasarkan nama (pertanyaan).
     *
     * @param string $name
     * @return \App\Models\Faq|null
     */
    public function getFaqByName($name)
    {
        return $this->model->where('question', 'like', '%' . $name . '%')->first();
    }

    /**
     * Mengambil FAQ berdasarkan status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFaqByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Mencari FAQ berdasarkan ID.
     *
     * @param int $id
     * @return \App\Models\Faq|null
     */
    public function findFaq($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("FAQ dengan ID {$id} tidak ditemukan.");
            return null;
        }
    }
    public function updateFaqStatus($id, $status)
    {
        $faq = $this->findFaq($id);

        if ($faq) {
            $faq->status = $status;
            $faq->save();
            return $faq;
        }
        return null;
    }

    public function shiftDisplayOrderUp($fromOrder, $toOrder = null, $excludeId = null)
    {
        $query = $this->model->where('display_order', '>=', $fromOrder);

        if ($toOrder !== null) {
            $query->where('display_order', '<', $toOrder);
        }

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->update([
            'display_order' => DB::raw('display_order + 1')
        ]);
    }

    public function shiftDisplayOrderDown($fromOrder, $toOrder, $excludeId)
    {
        return $this->model
            ->where('display_order', '>', $fromOrder)
            ->where('display_order', '<=', $toOrder)
            ->where('id', '!=', $excludeId)
            ->update([
                'display_order' => DB::raw('display_order - 1')
            ]);
    }
}
