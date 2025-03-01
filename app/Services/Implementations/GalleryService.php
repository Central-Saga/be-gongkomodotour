<?php

namespace App\Services\Implementations;

use App\Services\Contracts\GalleryServiceInterface;
use App\Repositories\Contracts\GalleryRepositoryInterface;
use App\Services\Contracts\AssetServiceInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GalleryService implements GalleryServiceInterface
{
    /**
     * @var GalleryRepositoryInterface
     */
    protected $repository;

    /**
     * @var AssetServiceInterface
     */
    protected $assetService;

    /**
     * Cache key for all galleries
     */
    const GALLERIES_ALL_CACHE_KEY = 'galleries.all';
    const GALLERIES_ACTIVE_CACHE_KEY = 'galleries.active';
    const GALLERIES_INACTIVE_CACHE_KEY = 'galleries.inactive';

    /**
     * Konstruktor GalleryService
     *
     * @param GalleryRepositoryInterface $repository
     * @param AssetServiceInterface $assetService
     */
    public function __construct(
        GalleryRepositoryInterface $repository,
        AssetServiceInterface $assetService
    ) {
        $this->repository = $repository;
        $this->assetService = $assetService;
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
     * Mengambil galeri berdasarkan status
     *
     * @param string $status
     * @return Collection
     */
    public function getGalleryByStatus($status)
    {
        return $this->repository->getGalleryByStatus($status);
    }

    /**
     * Mengambil semua galeri aktif
     *
     * @return Collection
     */
    public function getAllActiveGalleries()
    {
        return Cache::remember(self::GALLERIES_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getGalleryByStatus('Aktif');
        });
    }

    /**
     * Mengambil semua galeri tidak aktif
     *
     * @return Collection
     */
    public function getAllInactiveGalleries()
    {
        return Cache::remember(self::GALLERIES_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->repository->getGalleryByStatus('Non Aktif');
        });
    }

    /**
     * Membuat galeri baru
     *
     * @param array $data
     * @return Gallery
     */
    public function createGallery(array $data)
    {
        // Buat galeri baru
        $result = $this->repository->createGallery($data);

        if ($result) {
            $this->clearGalleryCaches();
            return $result;
        }

        return null;
    }

    /**
     * Mengupdate data galeri
     *
     * @param int $id
     * @param array $data
     * @return Gallery
     */
    public function updateGalleryBasicInfo($id, array $data)
    {
        // Update galeri
        $result = $this->repository->updateGallery($id, $data);

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
        // Update galeri
        $result = $this->repository->updateGallery($id, $data);

        if ($result) {
            $this->clearGalleryCaches();
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
        // Ambil semua asset terkait gallery
        $assets = $this->assetService->getAssets('gallery', $id);

        // Hapus semua asset terkait
        foreach ($assets as $asset) {
            $this->assetService->deleteAsset($asset->id);
        }

        // Hapus galeri
        $result = $this->repository->deleteGallery($id);

        if ($result) {
            $this->clearGalleryCaches();
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
        $result = $this->repository->updateGalleryStatus($id, $status);

        if ($result) {
            $this->clearGalleryCaches();
            return $result;
        }

        return null;
    }

    /**
     * Menghapus semua cache galeri
     *
     * @return void
     */
    public function clearGalleryCaches()
    {
        Cache::forget(self::GALLERIES_ALL_CACHE_KEY);
        Cache::forget(self::GALLERIES_ACTIVE_CACHE_KEY);
        Cache::forget(self::GALLERIES_INACTIVE_CACHE_KEY);
    }
}
