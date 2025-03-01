<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AssetResource;
use App\Http\Requests\AssetStoreRequest;
use App\Http\Requests\AssetUpdateRequest;
use App\Http\Requests\AssetMultipleStoreRequest;
use App\Services\Contracts\AssetServiceInterface;
use Illuminate\Routing\Controllers\HasMiddleware;

class AssetController extends Controller implements HasMiddleware
{
    /**
     * @var AssetServiceInterface
     */
    protected $assetService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */
    public static function middleware()
    {
        return [
            'permission:mengelola assets',
        ];
    }

    /**
     * Konstruktor AssetController.
     *
     * @param AssetServiceInterface $assetService
     */
    public function __construct(AssetServiceInterface $assetService)
    {
        $this->assetService = $assetService;
    }

    /**
     * Menampilkan semua asset untuk model tertentu.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $modelType = $request->query('model_type');
        $modelId = $request->query('model_id');

        if (!$modelType || !$modelId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Parameter model_type dan model_id diperlukan'
            ], 400);
        }

        $assets = $this->assetService->getAssets($modelType, $modelId);

        return AssetResource::collection($assets);
    }

    /**
     * Menambahkan asset baru ke model.
     *
     * @param AssetStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request dengan file upload:
     * - model_type: "gallery"
     * - model_id: 1
     * - file: [file.jpg]
     * - title: "Judul Asset"
     * - description: "Deskripsi asset"
     *
     * Contoh request dengan URL eksternal:
     * {
     *   "model_type": "gallery",
     *   "model_id": 1,
     *   "file_url": "https://example.com/image.jpg",
     *   "title": "Judul Asset",
     *   "description": "Deskripsi asset",
     *   "is_external": true
     * }
     */
    public function store(AssetStoreRequest $request)
    {
        $data = $request->validated();
        $modelType = $data['model_type'];
        $modelId = $data['model_id'];

        // Konversi is_external dari string ke boolean jika ada
        if (isset($data['is_external']) && is_string($data['is_external'])) {
            $data['is_external'] = filter_var($data['is_external'], FILTER_VALIDATE_BOOLEAN);
        }

        $asset = $this->assetService->addAsset($modelType, $modelId, $data);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan asset'
            ], 500);
        }

        return (new AssetResource($asset))
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil ditambahkan',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan asset tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $asset = $this->assetService->getAssetById($id);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return new AssetResource($asset);
    }

    /**
     * Memperbarui asset tertentu.
     *
     * @param AssetUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request:
     * {
     *   "title": "Judul Asset Baru",
     *   "description": "Deskripsi asset baru"
     * }
     */
    public function update(AssetUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $asset = $this->assetService->updateAsset($id, $data);

        if (!$asset) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return (new AssetResource($asset))
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil diperbarui',
            ]);
    }

    /**
     * Menghapus asset tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $result = $this->assetService->deleteAsset($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Asset tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Asset berhasil dihapus'
        ]);
    }

    /**
     * Menambahkan multiple asset ke model.
     *
     * @param AssetMultipleStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request dengan multiple files upload:
     * - model_type: "gallery"
     * - model_id: 1
     * - files[0]: [file1.jpg]
     * - files[1]: [file2.jpg]
     * - file_titles[0]: "Judul Gambar 1"
     * - file_titles[1]: "Judul Gambar 2"
     * - file_descriptions[0]: "Deskripsi gambar 1"
     * - file_descriptions[1]: "Deskripsi gambar 2"
     *
     * Contoh request dengan multiple URLs:
     * {
     *   "model_type": "gallery",
     *   "model_id": 1,
     *   "file_urls": [
     *     "https://example.com/image1.jpg",
     *     "https://example.com/image2.jpg"
     *   ],
     *   "file_url_titles": [
     *     "Judul Gambar 1",
     *     "Judul Gambar 2"
     *   ],
     *   "file_url_descriptions": [
     *     "Deskripsi gambar 1",
     *     "Deskripsi gambar 2"
     *   ],
     *   "is_external": true
     * }
     */
    public function storeMultiple(AssetMultipleStoreRequest $request)
    {
        $data = $request->validated();
        $modelType = $data['model_type'];
        $modelId = $data['model_id'];

        // Konversi is_external dari string ke boolean jika ada
        if (isset($data['is_external']) && is_string($data['is_external'])) {
            $data['is_external'] = filter_var($data['is_external'], FILTER_VALIDATE_BOOLEAN);
        }

        $assets = $this->assetService->addMultipleAssets($modelType, $modelId, $data);

        if (empty($assets)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menambahkan asset'
            ], 500);
        }

        return AssetResource::collection($assets)
            ->additional([
                'status' => 'success',
                'message' => 'Asset berhasil ditambahkan',
            ])
            ->response()
            ->setStatusCode(201);
    }
}
