<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Implementations/FaqService.php

namespace App\Services\Implementations;

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
        $result = $this->faqRepository->createFaq($data);
        Cache::forget(self::FAQ_ALL_CACHE_KEY);
        Cache::forget(self::FAQ_ACTIVE_CACHE_KEY);
        return $result;
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
        $result = $this->faqRepository->updateFaq($id, $data);
        Cache::forget(self::FAQ_ALL_CACHE_KEY);
        Cache::forget(self::FAQ_ACTIVE_CACHE_KEY);
        return $result;
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
        Cache::forget(self::FAQ_ALL_CACHE_KEY);
        Cache::forget(self::FAQ_ACTIVE_CACHE_KEY);
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
}