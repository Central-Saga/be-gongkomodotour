<?php

namespace App\Services\Implementations;

use App\Services\Contracts\CarouselServiceInterface;
use App\Repositories\Contracts\CarouselRepositoryInterface;
use App\Models\Asset;

class CarouselService implements CarouselServiceInterface
{
    protected $repository;

    public function __construct(CarouselRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCarousel()
    {
        return $this->repository->getAll()->load('assets');
    }

    public function getCarouselById($id)
    {
        return $this->repository->getById($id)->load('assets');
    }

    public function createCarousel(array $data)
    {
        // Pisahkan data carousel dan assets
        $carouselData = collect($data)->except('assets')->toArray();
        $assetsData = $data['assets'] ?? [];

        // Buat carousel
        $carousel = $this->repository->create($carouselData);

        // Buat assets jika ada
        if (!empty($assetsData)) {
            foreach ($assetsData as $assetData) {
                Asset::create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => get_class($carousel),
                    'title' => $assetData['title'] ?? null,
                    'description' => $assetData['description'] ?? null,
                    'file_path' => $assetData['file_url'] ?? null,
                    'file_url' => $assetData['file_url'] ?? null,
                    'is_external' => $assetData['is_external'] ?? true,
                ]);
            }
        }

        return $carousel->load('assets');
    }

    public function updateCarousel($id, array $data)
    {
        // Pisahkan data carousel dan assets
        $carouselData = collect($data)->except('assets')->toArray();
        $assetsData = $data['assets'] ?? [];

        // Update carousel
        $carousel = $this->repository->update($id, $carouselData);

        if ($carousel && !empty($assetsData)) {
            // Hapus assets lama
            $carousel->assets()->delete();

            // Buat assets baru
            foreach ($assetsData as $assetData) {
                Asset::create([
                    'assetable_id' => $carousel->id,
                    'assetable_type' => get_class($carousel),
                    'title' => $assetData['title'] ?? null,
                    'description' => $assetData['description'] ?? null,
                    'file_path' => $assetData['file_url'] ?? null,
                    'file_url' => $assetData['file_url'] ?? null,
                    'is_external' => $assetData['is_external'] ?? true,
                ]);
            }
        }

        return $carousel ? $carousel->load('assets') : null;
    }

    public function deleteCarousel($id)
    {
        $carousel = $this->repository->getById($id);
        if ($carousel) {
            // Hapus assets terlebih dahulu
            $carousel->assets()->delete();
            return $this->repository->delete($id);
        }
        return false;
    }

    public function getActiveCarousel()
    {
        return $this->repository->getActive()->load('assets');
    }

    public function getInactiveCarousel()
    {
        return $this->repository->getInactive()->load('assets');
    }

    public function getCarouselWithAssetsCount($count = 1)
    {
        return $this->repository->getWithAssetsCount($count);
    }

    public function getCarouselByOrder()
    {
        return $this->repository->getByOrder();
    }
}
