<?php

namespace App\Services\Implementations;

use App\Services\Contracts\GalleryServiceInterface;
use App\Repositories\Contracts\GalleryRepositoryInterface;
use App\Models\Asset;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
    const GALLERIES_ACTIVE_CACHE_KEY = 'galleries.active';
    const GALLERIES_INACTIVE_CACHE_KEY = 'galleries.inactive';

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
        // Pisahkan data file dari data galeri
        $fileData = null;
        if (isset($data['file'])) {
            $fileData = $data['file'];
            unset($data['file']);
        }

        // Buat galeri baru
        $result = $this->repository->createGallery($data);

        if ($result) {
            // Jika ada file, simpan sebagai asset
            if ($fileData) {
                $this->saveGalleryAsset($result, $fileData);
            }

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
        // Pisahkan data file dari data galeri
        $fileData = null;
        if (isset($data['file'])) {
            $fileData = $data['file'];
            unset($data['file']);
        }

        // Update galeri
        $result = $this->repository->updateGallery($id, $data);

        if ($result) {
            // Jika ada file baru, update asset
            if ($fileData) {
                // Hapus asset lama jika ada
                $this->deleteGalleryAssets($result);
                // Simpan asset baru
                $this->saveGalleryAsset($result, $fileData);
            }

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
        // Ambil galeri yang akan dihapus
        $gallery = $this->repository->getGalleryById($id);

        if ($gallery) {
            // Hapus semua asset terkait
            $this->deleteGalleryAssets($gallery);

            // Hapus galeri
            $result = $this->repository->deleteGallery($id);

            if ($result) {
                $this->clearGalleryCaches($id);
                return $result;
            }
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
        Cache::forget(self::GALLERIES_ACTIVE_CACHE_KEY);
        Cache::forget(self::GALLERIES_INACTIVE_CACHE_KEY);
    }

    /**
     * Menyimpan asset untuk galeri
     *
     * @param Gallery $gallery
     * @param mixed $fileData
     * @return Asset|null
     */
    protected function saveGalleryAsset($gallery, $fileData)
    {
        try {
            // Jika $fileData adalah instance UploadedFile
            if (is_object($fileData) && method_exists($fileData, 'getClientOriginalName')) {
                $fileName = time() . '_' . $fileData->getClientOriginalName();
                $filePath = $fileData->storeAs('galleries', $fileName, 'public');
                $fileUrl = Storage::url($filePath);

                // Buat asset baru
                return $gallery->assets()->create([
                    'title' => $gallery->title,
                    'description' => $gallery->description,
                    'file_path' => $filePath,
                    'file_url' => $fileUrl,
                ]);
            }
            // Jika $fileData adalah array dengan informasi file
            elseif (is_array($fileData) && isset($fileData['path']) && isset($fileData['url'])) {
                return $gallery->assets()->create([
                    'title' => $fileData['title'] ?? $gallery->title,
                    'description' => $fileData['description'] ?? $gallery->description,
                    'file_path' => $fileData['path'],
                    'file_url' => $fileData['url'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to save gallery asset: {$e->getMessage()}");
        }

        return null;
    }

    /**
     * Menghapus semua asset untuk galeri
     *
     * @param Gallery $gallery
     * @return void
     */
    protected function deleteGalleryAssets($gallery)
    {
        try {
            // Ambil semua asset
            $assets = $gallery->assets;

            foreach ($assets as $asset) {
                // Hapus file dari storage
                if (Storage::disk('public')->exists($asset->file_path)) {
                    Storage::disk('public')->delete($asset->file_path);
                }

                // Hapus record asset
                $asset->delete();
            }
        } catch (\Exception $e) {
            Log::error("Failed to delete gallery assets: {$e->getMessage()}");
        }
    }
}
