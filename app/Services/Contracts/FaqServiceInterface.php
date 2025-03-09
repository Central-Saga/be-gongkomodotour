<?php

namespace App\Services\Contracts;

interface FaqServiceInterface
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
     * Mengambil semua faq yang aktif.
     *
     * @return mixed
     */
    public function getActiveFaq();

    /**
     * Mengambil semua faq yang tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveFaq();
}