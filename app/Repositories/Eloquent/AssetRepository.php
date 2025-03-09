<?php

namespace App\Repositories\Eloquent;

use App\Models\Asset;
use App\Repositories\Contracts\AssetRepositoryInterface;

class AssetRepository implements AssetRepositoryInterface
{
    /**
     * @var Asset
     */
    protected $model;

    /**
     * AssetRepository constructor.
     *
     * @param Asset $model
     */
    public function __construct(Asset $model)
    {
        $this->model = $model;
    }

    /**
     * Get all assets
     *
     * @return Collection
     */
    public function getAllAssets()
    {
        return $this->model->all();
    }

    /**
     * Get asset by id
     *
     * @param int $id
     * @return Asset
     */
    public function getAssetById($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create asset
     *
     * @param array $data
     * @return Asset
     */
    public function createAsset(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update asset
     *
     * @param int $id
     * @param array $data
     * @return Asset
     */
    public function updateAsset($id, array $data)
    {
        $asset = $this->getAssetById($id);

        if ($asset) {
            $asset->update($data);
            return $asset;
        }

        return null;
    }

    /**
     * Delete asset
     *
     * @param int $id
     * @return bool
     */
    public function deleteAsset($id)
    {
        $asset = $this->getAssetById($id);

        if ($asset) {
            return $asset->delete();
        }

        return false;
    }
}
