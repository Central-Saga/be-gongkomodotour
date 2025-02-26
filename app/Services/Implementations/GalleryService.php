<?php

namespace App\Services\Implementations;

use App\Services\Contracts\GalleryServiceInterface;
use App\Repositories\Contracts\GalleryRepositoryInterface;

class GalleryService implements GalleryServiceInterface
{
    /**
     * @var GalleryRepositoryInterface
     */
    protected $repository;

    /**
     * Cache key for all galleries
     */
    const GALLERIES_ALL_CACHE_KEY = 'galleries.all';

    /**
     * Konstruktor GalleryService
     *
     * @param GalleryRepositoryInterface $repository
     */
    public function __construct(GalleryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Mengambil semua galeri
     *
     * @return Collection
     */
    public function getAllGalleries()
    {
        return Cache::remember(self::GALLERIES_ALL_CACHE_KEY, 3600, function () {
            return $this->repository->getAllGalleries();
        });
    }

    /**
     * Mengambil galeri berdasarkan ID
     *
     * @param int $id
     * @return Gallery
     */
    public function getGalleryById($id)
    {
        return $this->repository->getGalleryById($id);
    }

    /**
     * Mengambil galeri berdasarkan kategori
     *
     * @param string $category
     * @return Collection
     */
    public function getGalleryByCategory($category)
    {
        return $this->repository->getGalleryByCategory($category);
    }

    /**
     * Membuat galeri baru
     *
     * @param array $data
     * @return Gallery
     */
    public function createGallery(array $data)
    {
        $result = $this->repository->createGallery($data);
        if ($result) {
            $this->clearGalleryCaches();
            return $result;
        }
        return null;
    }

    /**
     * Mengupdate galeri
     *
     * @param int $id
     * @param array $data
     * @return Gallery
     */
    public function updateGallery($id, array $data)
    {
        $result = $this->repository->updateGallery($id, $data);
        if ($result) {
            $this->clearGalleryCaches($id);
            return $result;
        }
        return null;
    }

    /**
     * Menghapus galeri
     *
     * @param int $id
     * @return bool
     */
    public function deleteGallery($id)
    {
        $result = $this->repository->deleteGallery($id);
        if ($result) {
            $this->clearGalleryCaches($id);
            return $result;
        }
        return null;
    }

    /**
     * Mengupdate status galeri
     *
     * @param int $id
     * @param string $status
     * @return Gallery
     */
    public function updateGalleryStatus($id, $status)
    {
        return $this->repository->updateGalleryStatus($id, $status);
    }

    /**
     * Menghapus semua cache galeri
     *
     * @param int|null $id
     * @return void
     */
    public function clearGalleryCaches($id = null)
    {
        Cache::forget(self::GALLERIES_ALL_CACHE_KEY);
    }
}
