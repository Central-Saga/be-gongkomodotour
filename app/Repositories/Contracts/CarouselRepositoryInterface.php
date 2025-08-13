<?php

namespace App\Repositories\Contracts;

interface CarouselRepositoryInterface
{
    /**
     * Ambil semua carousel.
     */
    public function getAll();

    /**
     * Ambil carousel berdasarkan ID.
     */
    public function getById($id);

    /**
     * Buat carousel baru.
     */
    public function create(array $data);

    /**
     * Update carousel berdasarkan ID.
     */
    public function update($id, array $data);

    /**
     * Hapus carousel berdasarkan ID.
     */
    public function delete($id);

    /**
     * Ambil carousel aktif.
     */
    public function getActive();

    /**
     * Ambil carousel tidak aktif.
     */
    public function getInactive();

    /**
     * Ambil carousel dengan jumlah assets tertentu.
     */
    public function getWithAssetsCount($count = 1);

    /**
     * Ambil carousel berdasarkan urutan.
     */
    public function getByOrder();
}
