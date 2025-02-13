<?php

namespace App\Repositories\Contracts;

interface BlogRepositoryInterface
{
    /**
     * Mengambil semua blog.
     *
     * @return mixed
     */
    public function getAllBlog();

    /**
     * Mengambil blog berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBlogById($id);

    /**
     * Membuat blog baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBlog(array $data);

    /**
     * Memperbarui blog berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBlog($id, array $data);

    /**
     * Menghapus blog berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBlog($id);

    /**
     * Mengambil blog berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBlogByName($name);

    /**
     * Mengambil blog berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBlogByStatus($status);

    /**
     * Mencari blog berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return mixed
     */
    public function findBlog($id);
}