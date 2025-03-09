<?php

namespace App\Services\Contracts;

interface AssetServiceInterface
{
    /**
     * Get all assets for a specific model
     * @param string $modelType
     * @param int $modelId
     * @return Collection
     */
    public function getAssets($modelType, $modelId);

    /**
     * Get asset by id
     * @param int $id
     * @return Asset
     */
    public function getAssetById($id);

    /**
     * Add asset to a model
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return Asset
     */
    public function addAsset($modelType, $modelId, array $data);

    /**
     * Add multiple assets to a model
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return array
     */
    public function addMultipleAssets($modelType, $modelId, array $data);

    /**
     * Update asset
     * @param int $assetId
     * @param array $data
     * @return Asset
     */
    public function updateAsset($assetId, array $data);

    /**
     * Delete asset
     * @param int $assetId
     * @return bool
     */
    public function deleteAsset($assetId);

    /**
     * Get model instance by type and id
     * @param string $modelType
     * @param int $modelId
     * @return Model
     */
    public function getModelInstance($modelType, $modelId);
}
