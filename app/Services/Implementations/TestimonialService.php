<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\TestimonialServiceInterface;
use App\Repositories\Contracts\TestimonialRepositoryInterface;


class TestimonialService implements TestimonialServiceInterface
{
    protected $testimonialRepository;

    const TESTIMONIAL_ALL_CACHE_KEY = 'testimonial.all';
    const TESTIMONIAL_APPROVED_CACHE_KEY = 'testimonial.approved';
    const TESTIMONIAL_HIGHLIGHT_CACHE_KEY = 'testimonial.highlight';

    public function __construct(TestimonialRepositoryInterface $testimonialRepository)
    {
        $this->testimonialRepository = $testimonialRepository;
    }

    public function getAllTestimonial()
    {
        return Cache::remember(self::TESTIMONIAL_ALL_CACHE_KEY, 3600, function () {
            return $this->testimonialRepository->getAllTestimonial();
        });
    }

    public function getTestimonialById($id)
    {
        return $this->testimonialRepository->getTestimonialById($id);
    }

    public function createTestimonial(array $data)
    {
        $result = $this->testimonialRepository->createTestimonial($data);
        Cache::forget(self::TESTIMONIAL_ALL_CACHE_KEY);
        return $result;
    }

    public function updateTestimonial($id, array $data)
    {
        $result = $this->testimonialRepository->updateTestimonial($id, $data);
        Cache::forget(self::TESTIMONIAL_ALL_CACHE_KEY);
        return $result;
    }

    public function deleteTestimonial($id)
    {
        $result = $this->testimonialRepository->deleteTestimonial($id);
        Cache::forget(self::TESTIMONIAL_ALL_CACHE_KEY);
        return $result;
    }

    public function findTestimonial($id)
    {
        return $this->testimonialRepository->findTestimonial($id);
    }

    public function getTestimonialByApproved($approved)
    {
        $cacheKey = self::TESTIMONIAL_APPROVED_CACHE_KEY . '.' . ($approved ? 'true' : 'false');
        return Cache::remember($cacheKey, 3600, function () use ($approved) {
            return $this->testimonialRepository->getTestimonialByApproved($approved);
        });
    }

    public function getTestimonialByHighlight($highlight)
    {
        $cacheKey = self::TESTIMONIAL_HIGHLIGHT_CACHE_KEY . '.' . ($highlight ? 'true' : 'false');
        return Cache::remember($cacheKey, 3600, function () use ($highlight) {
            return $this->testimonialRepository->getTestimonialByHighlight($highlight);
        });
    }

    /**
     * Mengambil testimonial berdasarkan kombinasi is_approved dan is_highlight.
     *
     * @param bool|null $approved
     * @param bool|null $highlight
     * @return mixed
     */
    public function getTestimonialByFilters($approved = null, $highlight = null)
    {
        return $this->testimonialRepository->getTestimonialByFilters($approved, $highlight);
    }
}