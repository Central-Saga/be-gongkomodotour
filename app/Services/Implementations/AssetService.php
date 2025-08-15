<?php

namespace App\Services\Implementations;

use App\Models\Asset;
use App\Models\Gallery;
use App\Models\Boat;
use App\Models\Cabin;
use App\Models\Transaction;
use App\Models\Blog;
use App\Models\Trips;
use App\Models\Carousel;
use App\Services\Contracts\AssetServiceInterface;
use App\Repositories\Contracts\AssetRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AssetService implements AssetServiceInterface
{
    /**
     * @var AssetRepositoryInterface
     */
    protected $repository;

    /**
     * Model mapping
     */
    protected $modelMapping = [
        'gallery' => Gallery::class,
        'boat' => Boat::class,
        'cabin' => Cabin::class,
        'transaction' => Transaction::class,
        'blog' => Blog::class,
        'trip' => Trips::class,
        'carousel' => \App\Models\Carousel::class,
    ];

    /**
     * AssetService constructor.
     *
     * @param AssetRepositoryInterface $repository
     */
    public function __construct(AssetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all assets for a specific model
     *
     * @param string $modelType
     * @param int $modelId
     * @return Collection
     */
    public function getAssets($modelType, $modelId)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            return \collect([]);
        }

        return $model->assets;
    }

    /**
     * Get asset by id
     *
     * @param int $id
     * @return Asset
     */
    public function getAssetById($id)
    {
        return $this->repository->getAssetById($id);
    }

    /**
     * Add asset to a model
     *
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return Asset
     */
    public function addAsset($modelType, $modelId, array $data)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            return null;
        }

        // Proses file upload
        if (isset($data['file'])) {
            $file = $data['file'];
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs($modelType, $fileName, 'public');
            $fileUrl = Storage::url($filePath);

            // Buat asset baru dengan file fisik
            $assetData = [
                'assetable_id' => $model->id,
                'assetable_type' => get_class($model),
                'title' => $data['title'] ?? $model->title ?? $model->name ?? 'Asset',
                'description' => $data['description'] ?? $model->description ?? null,
                'file_path' => $filePath,
                'file_url' => $fileUrl,
                'is_external' => false,
            ];

            $asset = $this->repository->createAsset($assetData);
            Log::info('Created asset from uploaded file', ['asset_id' => $asset->id]);
            return $asset;
        }
        // Proses URL eksternal
        elseif (isset($data['file_url'])) {
            $fileUrl = $data['file_url'];
            $isExternal = $data['is_external'] ?? true;

            $assetData = [
                'assetable_id' => $model->id,
                'assetable_type' => get_class($model),
                'title' => $data['title'] ?? $model->title ?? $model->name ?? 'Asset',
                'description' => $data['description'] ?? $model->description ?? null,
                'file_path' => $isExternal ? null : ($data['path'] ?? null),
                'file_url' => $fileUrl,
                'is_external' => $isExternal,
            ];

            $asset = $this->repository->createAsset($assetData);
            Log::info('Created asset from external URL', ['asset_id' => $asset->id]);
            return $asset;
        }

        return null;
    }

    /**
     * Add multiple assets to a model
     *
     * @param string $modelType
     * @param int $modelId
     * @param array $data
     * @return array
     */
    public function addMultipleAssets($modelType, $modelId, array $data)
    {
        $model = $this->getModelInstance($modelType, $modelId);

        if (!$model) {
            return [];
        }

        $createdAssets = [];

        // Proses multiple files upload
        if (isset($data['files']) && is_array($data['files'])) {
            foreach ($data['files'] as $index => $file) {
                $assetData = [
                    'file' => $file,
                    'title' => $data['file_titles'][$index] ?? ($model->title ?? $model->name ?? 'Asset') . ' - ' . ($index + 1),
                    'description' => $data['file_descriptions'][$index] ?? null
                ];

                $asset = $this->addAsset($modelType, $modelId, $assetData);
                if ($asset) {
                    $createdAssets[] = $asset;
                }
            }
        }

        // Proses multiple file URLs
        if (isset($data['file_urls']) && is_array($data['file_urls'])) {
            foreach ($data['file_urls'] as $index => $fileUrl) {
                $assetData = [
                    'file_url' => $fileUrl,
                    'is_external' => $data['is_external'] ?? true,
                    'title' => $data['file_url_titles'][$index] ?? ($model->title ?? $model->name ?? 'Asset') . ' - ' . ($index + 1),
                    'description' => $data['file_url_descriptions'][$index] ?? null
                ];

                $asset = $this->addAsset($modelType, $modelId, $assetData);
                if ($asset) {
                    $createdAssets[] = $asset;
                }
            }
        }

        return $createdAssets;
    }

    /**
     * Update asset
     *
     * @param int $assetId
     * @param array $data
     * @return Asset
     */
    public function updateAsset($assetId, array $data)
    {
        $asset = $this->getAssetById($assetId);

        if (!$asset) {
            return null;
        }

        $updateData = [];

        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }

        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }

        if (!empty($updateData)) {
            return $this->repository->updateAsset($assetId, $updateData);
        }

        return $asset;
    }

    /**
     * Delete asset
     *
     * @param int $assetId
     * @return bool
     */
    public function deleteAsset($assetId)
    {
        $asset = $this->getAssetById($assetId);

        if (!$asset) {
            return false;
        }

        // Hapus file dari storage hanya jika bukan URL eksternal dan file_path ada
        if (!$asset->is_external && $asset->file_path && Storage::disk('public')->exists($asset->file_path)) {
            Storage::disk('public')->delete($asset->file_path);
        }

        // Hapus record asset
        return $this->repository->deleteAsset($assetId);
    }

    /**
     * Get model instance by type and id
     *
     * @param string $modelType
     * @param int $modelId
     * @return Model
     */
    public function getModelInstance($modelType, $modelId)
    {
        $modelClass = $this->getModelClass($modelType);

        if (!$modelClass) {
            return null;
        }

        return $modelClass::find($modelId);
    }

    /**
     * Get model class by type
     *
     * @param string $modelType
     * @return string|null
     */
    protected function getModelClass($modelType)
    {
        return $this->modelMapping[strtolower($modelType)] ?? null;
    }
}
