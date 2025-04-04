<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Implementations/FaqService.php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\FaqServiceInterface;
use App\Repositories\Contracts\FaqRepositoryInterface;

class FaqService implements FaqServiceInterface
{
    protected $faqRepository;

    const FAQ_ALL_CACHE_KEY      = 'faq.all';
    const FAQ_ACTIVE_CACHE_KEY   = 'faq.active';
    const FAQ_INACTIVE_CACHE_KEY = 'faq.inactive';

    /**
     * Konstruktor FaqService.
     *
     * @param FaqRepositoryInterface $faqRepository
     */
    public function __construct(FaqRepositoryInterface $faqRepository)
    {
        $this->faqRepository = $faqRepository;
    }

    /**
     * Mengambil semua FAQ.
     *
     * @return mixed
     */
    public function getAllFaq()
    {
        return Cache::remember(self::FAQ_ALL_CACHE_KEY, 3600, function () {
            return $this->faqRepository->getAllFaq();
        });
    }

    /**
     * Mengambil boat berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFaqByName($name)
    {
        return $this->faqRepository->getFaqByName($name);
    }

    /**
     * Mengambil FAQ berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFaqById($id)
    {
        return $this->faqRepository->getFaqById($id);
    }

    /**
     * Membuat FAQ baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFaq(array $data)
    {
        try {
            DB::beginTransaction();

            $displayOrder = (int) $data['display_order'];
            if ($displayOrder < 1 || $displayOrder > 6) {
                throw new \Exception('Display order harus antara 1-6');
            }
            $this->faqRepository->shiftDisplayOrderUp(
                $displayOrder
            );

            $result = $this->faqRepository->createFaq($data);
            $this->clearFaqCaches();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            Log::error("Error creating FAQ: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Memperbarui FAQ berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFaq($id, array $data)
    {
        try {
            DB::beginTransaction();

            $newDisplayOrder = (int) $data['display_order'];
            if ($newDisplayOrder < 1 || $newDisplayOrder > 6) {
                throw new \Exception('Display order harus antara 1-6');
            }

            $currentFaq = $this->faqRepository->getFaqById($id);
            $oldDisplayOrder = (int) $currentFaq->display_order;

            // Jika display_order berubah
            if ($newDisplayOrder !== $oldDisplayOrder) {
                if ($newDisplayOrder > $oldDisplayOrder) {
                    // Geser ke bawah: kurangi display_order FAQ yang berada di antara old dan new
                    $this->faqRepository->shiftDisplayOrderDown($oldDisplayOrder, $newDisplayOrder, $id);
                } else {
                    // Geser ke atas: tambah display_order FAQ yang berada di antara new dan old
                    $this->faqRepository->shiftDisplayOrderUp($newDisplayOrder, $oldDisplayOrder, $id);
                }
            }

            $result = $this->faqRepository->updateFaq($id, $data);
            $this->clearFaqCaches();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            Log::error("Error updating FAQ: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menghapus FAQ berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFaq($id)
    {
        $result = $this->faqRepository->deleteFaq($id);
        $this->clearFaqCaches();
        return $result;
    }

    /**
     * Mengambil FAQ berdasarkan pertanyaan.
     *
     * @param string $question
     * @return mixed
     */
    public function getFaqByQuestion($question)
    {
        return $this->faqRepository->getFaqByName($question);
    }

    /**
     * Mengambil FAQ berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFaqByStatus($status)
    {
        return $this->faqRepository->getFaqByStatus($status);
    }

    /**
     * Mengambil semua FAQ yang aktif.
     *
     * @return mixed
     */
    public function getActiveFaq()
    {
        return Cache::remember(self::FAQ_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->faqRepository->getFaqByStatus('Aktif');
        });
    }

    /**
     * Mengambil semua FAQ yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveFaq()
    {
        return Cache::remember(self::FAQ_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->faqRepository->getFaqByStatus('Non Aktif');
        });
    }

    public function clearFaqCaches()
    {
        Cache::forget(self::FAQ_ALL_CACHE_KEY);
        Cache::forget(self::FAQ_ACTIVE_CACHE_KEY);
        Cache::forget(self::FAQ_INACTIVE_CACHE_KEY);
    }
}
