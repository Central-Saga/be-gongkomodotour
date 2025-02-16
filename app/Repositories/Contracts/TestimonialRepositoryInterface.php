<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Repositories/Contracts/TestimonialRepositoryInterface.php

namespace App\Repositories\Contracts;

interface TestimonialRepositoryInterface
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
     * Mencari testimonial berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findTestimonial($id);

    /**
     * Mengambil testimonial berdasarkan nilai is_approved.
     *
     * @param bool $approved
     * @return mixed
     */
    public function getTestimonialByApproved($approved);

    /**
     * Mengambil testimonial berdasarkan nilai is_highlight.
     *
     * @param bool $highlight
     * @return mixed
     */
    public function getTestimonialByHighlight($highlight);

    /**
     * Mengambil testimonial berdasarkan kriteria tertentu.
     *
     * @param bool $approved
     * @param bool $highlight
     * @return mixed
     */
    public function getTestimonialByFilters($approved = null, $highlight = null);

}