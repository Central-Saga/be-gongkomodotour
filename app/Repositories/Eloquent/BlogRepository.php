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
        return $this->model->with('author', 'assets')->get();
    }

    /**
     * Mengambil blog berdasarkan ID.
     *
     * @param int $id
     * @return Blog|null
     */
    public function getBlogById($id)
    {
        return $this->model->with('author', 'assets')->find($id);
    }

    /**
     * Membuat blog baru.
     *
     * @param array $data
     * @return Blog
     */
    public function createBlog(array $data)
    {
        $blog = $this->model->create($data);
        $blog->load('author', 'assets');
        return $blog;
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
            $blog->load('author', 'assets');
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
        $blog = $this->model->find($id);
        if ($blog) {
            $blog->delete();
        }
        return $blog;
    }

    /**
     * Mengambil blog berdasarkan nama.
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBlogByName($name)
    {
        return $this->model->where('title', 'like', "%{$name}%")->with('author', 'assets')->get();
    }

    /**
     * Mengambil blog berdasarkan status.
     *
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getBlogByStatus($status)
    {
        return $this->model->where('status', $status)->with('author', 'assets')->get();
    }

    /**
     * Mencari blog berdasarkan kriteria tertentu.
     *
     * @param int $id
     * @return Blog|null
     */
    public function findBlog($id)
    {
        return $this->model->with('author', 'assets')->find($id);
    }
}
