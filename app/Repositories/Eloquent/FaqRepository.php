<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Repositories/Eloquent/FaqRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Faq;
use App\Repositories\Contracts\FaqRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class FaqRepository implements FaqRepositoryInterface
{
    /**
     * Mengambil semua FAQ.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllFaq()
    {
        return Faq::all();
    }

    /**
     * Mengambil FAQ berdasarkan ID.
     *
     * @param int $id
     * @return \App\Models\Faq
     */
    public function getFaqById($id)
    {
        return Faq::findOrFail($id);
    }

    /**
     * Membuat FAQ baru.
     *
     * @param array $data
     * @return \App\Models\Faq
     */
    public function createFaq(array $data)
    {
        return Faq::create($data);
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
        return Faq::where('question', 'like', '%' . $name . '%')->first();
    }

    /**
     * Mengambil FAQ berdasarkan status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFaqByStatus($status)
    {
        return Faq::where('status', $status)->get();
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
            return Faq::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("FAQ dengan ID {$id} tidak ditemukan.");
            return null;
        }
    }
}