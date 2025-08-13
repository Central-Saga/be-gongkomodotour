<?php

namespace App\Services\Contracts;

interface CarouselServiceInterface
{
    /**
     * Ambil semua carousel.
     */
    public function getAllCarousel();

    /**
     * Ambil carousel berdasarkan ID.
     */
    public function getCarouselById($id);

    /**
     * Buat carousel baru.
     */
    public function createCarousel(array $data);

    /**
     * Update carousel berdasarkan ID.
     */
    public function updateCarousel($id, array $data);

    /**
     * Hapus carousel berdasarkan ID.
     */
    public function deleteCarousel($id);

    /**
     * Ambil carousel aktif.
     */
    public function getActiveCarousel();

    /**
     * Ambil carousel tidak aktif.
     */
    public function getInactiveCarousel();

    /**
     * Ambil carousel dengan jumlah assets tertentu.
     */
    public function getCarouselWithAssetsCount($count = 1);

    /**
     * Ambil carousel berdasarkan urutan.
     */
    public function getCarouselByOrder();
}
