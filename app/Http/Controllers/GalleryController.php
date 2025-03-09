<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryResource;
use App\Http\Requests\GalleryStoreRequest;
use App\Http\Requests\GalleryUpdateRequest;
use App\Http\Requests\GalleryStatusUpdateRequest;
use App\Services\Contracts\GalleryServiceInterface;
use App\Services\Contracts\AssetServiceInterface;

class GalleryController extends Controller
{
    /**
     * @var GalleryServiceInterface
     */
    protected $galleryService;

    /**
     * @var AssetServiceInterface
     */
    protected $assetService;

    /**
     * Get the middleware the controller should use.
     *
     * @return array
     */

    /**
     * Konstruktor GalleryController.
     *
     * @param GalleryServiceInterface $galleryService
     * @param AssetServiceInterface $assetService
     */
    public function __construct(
        GalleryServiceInterface $galleryService,
        AssetServiceInterface $assetService
    ) {
        $this->galleryService = $galleryService;
        $this->assetService = $assetService;
    }

    /**
     * Menampilkan daftar galeri.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Ambil parameter kategori dari query string
        $category = $request->query('category');
        $status = $request->query('status');

        if ($category) {
            $galleries = $this->galleryService->getGalleryByCategory($category);
        } elseif ($status !== null) {
            if ($status == '1') {
                $galleries = $this->galleryService->getAllActiveGalleries();
            } elseif ($status == '0') {
                $galleries = $this->galleryService->getAllInactiveGalleries();
            } else {
                return response()->json(['message' => 'Invalid status parameter'], 400);
            }
        } else {
            $galleries = $this->galleryService->getAllGalleries();
        }

        return GalleryResource::collection($galleries);
    }

    /**
     * Menyimpan galeri baru.
     *
     * @param GalleryStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request:
     * {
     *   "title": "Judul Galeri",
     *   "description": "Deskripsi galeri",
     *   "category": "Kategori Galeri",
     *   "status": "Aktif"
     * }
     */
    public function store(GalleryStoreRequest $request)
    {
        $data = $request->validated();
        $gallery = $this->galleryService->createGallery($data);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat galeri'
            ], 500);
        }

        return (new GalleryResource($gallery))
            ->additional([
                'status' => 'success',
                'message' => 'Galeri berhasil dibuat',
            ])
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan galeri tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        $gallery = $this->galleryService->getGalleryById($id);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        // Load assets jika diperlukan
        $includeAssets = request()->query('include_assets', false);
        if ($includeAssets) {
            $assets = $this->assetService->getAssets('gallery', $id);
            $gallery->setRelation('assets', $assets);
        }

        return new GalleryResource($gallery);
    }

    /**
     * Memperbarui data galeri tertentu.
     *
     * @param GalleryUpdateRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     *
     * Contoh request:
     * {
     *   "title": "Judul Galeri Baru",
     *   "description": "Deskripsi galeri baru",
     *   "category": "Kategori Galeri Baru",
     *   "status": "Aktif"
     * }
     */
    public function update(GalleryUpdateRequest $request, string $id)
    {
        $data = $request->validated();
        $gallery = $this->galleryService->updateGalleryBasicInfo($id, $data);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        return (new GalleryResource($gallery))
            ->additional([
                'status' => 'success',
                'message' => 'Galeri berhasil diperbarui',
            ]);
    }

    /**
     * Menghapus galeri tertentu.
     *
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $result = $this->galleryService->deleteGallery($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Galeri berhasil dihapus'
        ]);
    }

    /**
     * Memperbarui status galeri.
     *
     * @param string $id
     * @param GalleryStatusUpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(string $id, GalleryStatusUpdateRequest $request)
    {
        $data = $request->validated();
        $gallery = $this->galleryService->updateGalleryStatus($id, $data['status']);

        if (!$gallery) {
            return response()->json([
                'status' => 'error',
                'message' => 'Galeri tidak ditemukan'
            ], 404);
        }

        return (new GalleryResource($gallery))
            ->additional([
                'status' => 'success',
                'message' => 'Status galeri berhasil diperbarui',
            ]);
    }
}
