<?php

namespace App\Services\Contracts;

interface GalleryServiceInterface
{
    /**
     * Get all galleries
     * @return Collection
     */
    public function getAllGalleries();

    /**
     * Get gallery by id
     * @param int $id
     * @return Gallery
     */
    public function getGalleryById($id);

    /**
     * Get gallery by category
     * @param string $category
     * @return Collection
     */
    public function getGalleryByCategory($category);

    /**
     * Create gallery
     * @param array $data
     * @return Gallery
     */
    public function createGallery(array $data);

    /**
     * Update gallery
     * @param int $id
     * @param array $data
     * @return Gallery
     */
    public function updateGallery($id, array $data);

    /**
     * Delete gallery
     * @param int $id
     * @return bool
     */
    public function deleteGallery($id);


    /**
     * Update gallery status
     * @param int $id
     * @param string $status
     * @return Gallery
     */
    public function updateGalleryStatus($id, $status);
}
