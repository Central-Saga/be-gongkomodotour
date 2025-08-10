<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Contracts/BlogServiceInterface.php

namespace App\Services\Contracts;

interface BlogServiceInterface
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
     * Mengambil blog berdasarkan judul.
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
     * Mengambil semua blog yang dipublikasikan.
     *
     * @param string|null $category
     * @return mixed
     */
    public function getPublishedBlog($category = null);

    /**
     * Mengambil semua blog yang berstatus draft.
     *
     * @param string|null $category
     * @return mixed
     */
    public function getDraftBlog($category = null);

    /**
     * Mengambil blog berdasarkan kategori.
     *
     * @param string $category
     * @return mixed
     */
    public function getBlogByCategory($category);

    /**
     * Mereset semua cache blog.
     *
     * @return void
     */
    public function resetBlogCache();
}
