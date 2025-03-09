<?php
// filepath: /c:/laragon/www/be-gongkomodotour/app/Services/Implementations/BlogService.php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\BlogServiceInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;

class BlogService implements BlogServiceInterface
{
    protected $blogRepository;

    const BLOG_ALL_CACHE_KEY       = 'blog.all';
    const BLOG_PUBLISHED_CACHE_KEY = 'blog.published';
    const BLOG_DRAFT_CACHE_KEY     = 'blog.draft';

    /**
     * Konstruktor BlogService.
     *
     * @param BlogRepositoryInterface $blogRepository
     */
    public function __construct(BlogRepositoryInterface $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    /**
     * Mengambil semua blog.
     *
     * @return mixed
     */
    public function getAllBlog()
    {
        return Cache::remember(self::BLOG_ALL_CACHE_KEY, 3600, function () {
            return $this->blogRepository->getAllBlog();
        });
    }

    /**
     * Mengambil blog berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBlogById($id)
    {
        return $this->blogRepository->getBlogById($id);
    }

    /**
     * Membuat blog baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBlog(array $data)
    {
        $result = $this->blogRepository->createBlog($data);
        Cache::forget(self::BLOG_ALL_CACHE_KEY);
        Cache::forget(self::BLOG_PUBLISHED_CACHE_KEY);
        Cache::forget(self::BLOG_DRAFT_CACHE_KEY);
        return $result;
    }

    /**
     * Memperbarui blog berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBlog($id, array $data)
    {
        $result = $this->blogRepository->updateBlog($id, $data);
        Cache::forget(self::BLOG_ALL_CACHE_KEY);
        Cache::forget(self::BLOG_PUBLISHED_CACHE_KEY);
        Cache::forget(self::BLOG_DRAFT_CACHE_KEY);
        return $result;
    }

    /**
     * Menghapus blog berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function deleteBlog($id)
    {
        $result = $this->blogRepository->deleteBlog($id);
        Cache::forget(self::BLOG_ALL_CACHE_KEY);
        Cache::forget(self::BLOG_PUBLISHED_CACHE_KEY);
        Cache::forget(self::BLOG_DRAFT_CACHE_KEY);
        return $result;
    }

    /**
     * Mengambil blog berdasarkan judul.
     *
     * @param string $name
     * @return mixed
     */
    public function getBlogByName($name)
    {
        return $this->blogRepository->getBlogByName($name);
    }

    /**
     * Mengambil blog berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBlogByStatus($status)
    {
        return $this->blogRepository->getBlogByStatus($status);
    }

    /**
     * Mengambil semua blog yang dipublikasikan.
     *
     * @return mixed
     */
    public function getPublishedBlog()
    {
        return Cache::remember(self::BLOG_PUBLISHED_CACHE_KEY, 3600, function () {
            return $this->blogRepository->getBlogByStatus('published');
        });
    }

    /**
     * Mengambil semua blog yang berstatus draft.
     *
     * @return mixed
     */
    public function getDraftBlog()
    {
        return Cache::remember(self::BLOG_DRAFT_CACHE_KEY, 3600, function () {
            return $this->blogRepository->getBlogByStatus('draft');
        });
    }
}