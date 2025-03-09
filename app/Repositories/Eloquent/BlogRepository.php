<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Repositories/Eloquent/BlogRepository.php

namespace App\Repositories\Eloquent;

use App\Models\Blog;
use App\Repositories\Contracts\BlogRepositoryInterface;

class BlogRepository implements BlogRepositoryInterface
{
    protected $model;

    public function __construct(Blog $blog)
    {
        $this->model = $blog;
    }

    /**
     * Mengambil semua blog.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAllBlog()
    {
        return $this->model->all();
    }

    /**
     * Mengambil blog berdasarkan ID.
     *
     * @param int $id
     * @return Blog|null
     */
    public function getBlogById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Membuat blog baru.
     *
     * @param array $data
     * @return Blog
     */
    public function createBlog(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Memperbarui blog berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Blog|null
     */
    public function updateBlog($id, array $data)
    {
        $blog = $this->model->find($id);
        if ($blog) {
            $blog->update($data);
        }
        return $blog;
    }

    /**
     * Menghapus blog berdasarkan ID.
     *
     * @param int $id
     * @return int
     */
    public function deleteBlog($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Mengambil blog berdasarkan nama.
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBlogByName($name)
    {
        return $this->model->where('title', 'like', "%{$name}%")->get();
    }

    /**
     * Mengambil blog berdasarkan status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBlogByStatus($status)
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Mencari blog berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return Blog|null
     */
    public function findBlog($id)
    {
        return $this->model->find($id);
    }
}