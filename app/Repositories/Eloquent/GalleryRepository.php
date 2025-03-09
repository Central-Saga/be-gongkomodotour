<?php

namespace App\Repositories\Eloquent;

use App\Models\Gallery;
use App\Repositories\Contracts\GalleryRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class GalleryRepository implements GalleryRepositoryInterface
{
    /**
     * @var Gallery
     */
    protected $model;

    /**
     * Konstruktor GalleryRepository.
     *
     * @param Gallery $model
     */
    public function __construct(Gallery $model)
    {
        $this->model = $model;
    }

    /**
     * Mengambil semua galleries.
     *
     * @return Collection
     */
    public function getAllGalleries()
    {
        return $this->model->latest()->get();
    }

    /**
     * Mengambil gallery berdasarkan ID.
     *
     * @param int $id
     * @return Gallery
     */
    public function getGalleryById($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Gallery with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengambil gallery berdasarkan kategori.
     *
     * @param string $category
     * @return Collection
     */
    public function getGalleryByCategory($category)
    {
        return $this->model->where('category', $category)->latest()->get();
    }

    /**
     * Mengambil gallery berdasarkan status.
     *
     * @param string $status
     * @return Collection
     */
    public function getGalleryByStatus($status)
    {
        return $this->model->where('status', $status)->latest()->get();
    }

    /**
     * Membuat gallery baru.
     *
     * @param array $data
     * @return Gallery
     */
    public function createGallery(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (\Exception $e) {
            Log::error("Failed to create gallery: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Memperbarui gallery berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return Gallery
     */
    public function updateGallery($id, array $data)
    {
        $gallery = $this->findGallery($id);
        if ($gallery) {
            try {
                $gallery->update($data);
                return $gallery->fresh();
            } catch (\Exception $e) {
                Log::error("Failed to update gallery with ID {$id}: {$e->getMessage()}");
                return null;
            }
        }
        return null;
    }

    /**
     * Menghapus gallery berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteGallery($id)
    {
        $gallery = $this->findGallery($id);
        if ($gallery) {
            try {
                $gallery->delete();
                return true;
            } catch (\Exception $e) {
                Log::error("Failed to delete gallery with ID {$id}: {$e->getMessage()}");
                return false;
            }
        }
        return false;
    }

    /**
     * Helper method untuk menemukan gallery berdasarkan ID.
     *
     * @param int $id
     * @return Gallery
     */
    protected function findGallery($id)
    {
        try {
            return $this->model->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error("Gallery with ID {$id} not found.");
            return null;
        }
    }

    /**
     * Mengupdate status gallery berdasarkan ID.
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateGalleryStatus($id, $status)
    {
        $gallery = $this->findGallery($id);
        if ($gallery) {
            $gallery->status = $status;
            $gallery->save();
            return true;
        }
        return false;
    }
}
