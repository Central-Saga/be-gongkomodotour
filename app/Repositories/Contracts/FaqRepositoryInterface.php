<?php

namespace App\Repositories\Contracts;

interface FaqRepositoryInterface
{
    /**
     * Mengambil semua faq.
     *
     * @return mixed
     */
    public function getAllFaq();

    /**
     * Mengambil faq berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getFaqById($id);

    /**
     * Membuat faq baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createFaq(array $data);

    /**
     * Memperbarui faq berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateFaq($id, array $data);

    /**
     * Menghapus faq berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteFaq($id);

    /**
     * Mengambil faq berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getFaqByName($name);

    /**
     * Mengambil faq berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getFaqByStatus($status);

    /**
     * Mencari faq berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findFaq($id);

    /**
     * Menggeser display order ke atas.
     *
     * @param int $fromOrder
     * @param int|null $toOrder
     * @param int|null $excludeId
     * @return mixed
     */
    public function shiftDisplayOrderUp($fromOrder, $toOrder = null, $excludeId = null);

    /**
     * Menggeser display order ke bawah.
     *
     * @param int $fromOrder
     * @param int $toOrder
     * @param int $excludeId
     * @return mixed
     */
    public function shiftDisplayOrderDown($fromOrder, $toOrder, $excludeId);
}
