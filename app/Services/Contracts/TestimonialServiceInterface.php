<?php

namespace App\Services\Contracts;

interface TestimonialServiceInterface
{
    /**
     * Mengambil semua testimonial.
     *
     * @return mixed
     */
    public function getAllTestimonial();

    /**
     * Mengambil testimonial berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getTestimonialById($id);

    /**
     * Membuat testimonial baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createTestimonial(array $data);

    /**
     * Memperbarui testimonial berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateTestimonial($id, array $data);

    /**
     * Menghapus testimonial berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteTestimonial($id);

    /**
     * Mencari testimonial berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function findTestimonial($id);

    /**
     * Mengambil testimonial berdasarkan nilai is_approved.
     *
     * @param mixed $approved Nilai yang dapat dikonversi ke boolean.
     * @return mixed
     */
    public function getTestimonialByApproved($approved);

    /**
     * Mengambil testimonial berdasarkan nilai is_highlight.
     *
     * @param mixed $highlight Nilai yang dapat dikonversi ke boolean.
     * @return mixed
     */
    public function getTestimonialByHighlight($highlight);

    /**
     * Mengambil testimonial berdasarkan kombinasi is_approved dan is_highlight.
     *
     * @param mixed $approved Nilai yang dapat dikonversi ke boolean.
     * @param mixed $highlight Nilai yang dapat dikonversi ke boolean.
     * @return mixed
     */
    public function getTestimonialByFilters($approved = null, $highlight = null);
}