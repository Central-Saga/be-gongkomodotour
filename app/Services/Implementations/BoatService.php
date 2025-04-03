<?php

namespace App\Services\Implementations;

use Illuminate\Support\Facades\Cache;
use App\Services\Contracts\BoatServiceInterface;
use App\Repositories\Contracts\BoatRepositoryInterface;
use App\Repositories\Contracts\CabinRepositoryInterface;
use App\Services\Contracts\AssetServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;


class BoatService implements BoatServiceInterface
{
    protected $boatRepository;
    protected $cabinRepository;
    protected $assetService;

    const BOAT_ALL_CACHE_KEY = 'boat.all';
    const BOAT_ACTIVE_CACHE_KEY = 'boat.active';
    const BOAT_INACTIVE_CACHE_KEY = 'boat.inactive';

    /**
     * Konstruktor Boatervice.
     *
     * @param BoatRepositoryInterface $boatRepository
     * @param CabinRepositoryInterface $cabinRepository
     * @param AssetServiceInterface $assetService
     */
    public function __construct(
        BoatRepositoryInterface $boatRepository,
        CabinRepositoryInterface $cabinRepository,
        AssetServiceInterface $assetService
    ) {
        $this->boatRepository = $boatRepository;
        $this->cabinRepository = $cabinRepository;
        $this->assetService = $assetService;
    }

    /**
     * Mengambil semua boat.
     *
     * @return mixed
     */
    public function getAllBoat()
    {
        return Cache::remember(self::BOAT_ALL_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getAllBoat();
        });
    }

    /**
     * Mengambil boat berdasarkan ID.
     *
     * @param int $id
     * @return mixed
     */
    public function getBoatById($id)
    {
        return $this->boatRepository->getBoatById($id);
    }

    /**
     * Mengambil boat berdasarkan nama.
     *
     * @param string $name
     * @return mixed
     */
    public function getBoatByName($name)
    {
        return $this->boatRepository->getBoatByName($name);
    }

    /**
     * Mengambil boat berdasarkan status.
     *
     * @param string $status
     * @return mixed
     */
    public function getBoatByStatus($status)
    {
        return $this->boatRepository->getBoatByStatus($status);
    }

    /**
     * Mengambil boat dengan status aktif.
     *
     * @return mixed
     */
    public function getActiveBoat()
    {
        return Cache::remember(self::BOAT_ACTIVE_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getBoatByStatus('Aktif');
        });
    }

    /**
     * Mengambil boat dengan status tidak aktif.
     *
     * @return mixed
     */
    public function getInactiveBoat()
    {
        return Cache::remember(self::BOAT_INACTIVE_CACHE_KEY, 3600, function () {
            return $this->boatRepository->getBoatByStatus('Non Aktif');
        });
    }

    /**
     * Membuat boat baru.
     *
     * @param array $data
     * @return mixed
     */
    public function createBoat(array $data)
    {
        try {
            DB::beginTransaction();

            // Buat boat utama
            $boatData = Arr::only($data, [
                'boat_name',
                'spesification',
                'cabin_information',
                'facilities',
                'status'
            ]);
            $boat = $this->boatRepository->createBoat($boatData);

            // Buat cabins jika ada
            if (isset($data['cabins'])) {
                foreach ($data['cabins'] as $cabin) {
                    $cabin['boat_id'] = $boat->id;
                    $this->cabinRepository->createCabin($cabin);
                }
            }

            // Jika request memiliki assets, buat masing-masing asset
            if (isset($data['assets'])) {
                foreach ($data['assets'] as $asset) {
                    $assetData = array_merge($asset, [
                        'model_type' => 'boat',
                        'model_id' => $boat->id
                    ]);
                    $this->assetService->addAsset('boat', $boat->id, $assetData);
                }
            }

            DB::commit();

            // Clear all related caches
            $this->clearBoatCaches();

            return $boat->fresh(['cabin', 'assets']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to create boat: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Memperbarui boat berdasarkan ID.
     *
     * @param int $id
     * @param array $data
     * @return mixed
     */
    public function updateBoat($id, array $data)
    {
        try {
            DB::beginTransaction();

            // Update data boat utama secara parsial
            $boatData = Arr::only($data, [
                'boat_name',
                'spesification',
                'cabin_information',
                'facilities',
                'status'
            ]);
            $boat = $this->boatRepository->updateBoat($id, $boatData);

            // Update cabins secara parsial
            if (isset($data['cabins'])) {
                Log::info($data['cabins']);
                $payloadCabinIds = [];
                Log::info($payloadCabinIds);
                foreach ($data['cabins'] as $cabinData) {
                    Log::info($cabinData);
                    $cabinData['boat_id'] = $boat->id;
                    if (isset($cabinData['id'])) {
                        $this->cabinRepository->updateCabin($cabinData['id'], $cabinData);
                        $payloadCabinIds[] = $cabinData['id'];
                    } else {
                        $newCabin = $this->cabinRepository->createCabin($cabinData);
                        if ($newCabin && isset($newCabin->id)) {
                            $payloadCabinIds[] = $newCabin->id;
                        }
                    }
                }
                $this->cabinRepository->deleteCabinsNotIn($boat->id, $payloadCabinIds);
            }

            // Update assets secara parsial jika ada di payload
            if (isset($data['assets'])) {
                $payloadAssetIds = [];
                foreach ($data['assets'] as $assetData) {
                    $assetData['model_type'] = 'boat';
                    $assetData['model_id'] = $boat->id;

                    if (isset($assetData['id'])) {
                        $this->assetService->updateAsset($assetData['id'], $assetData);
                        $payloadAssetIds[] = $assetData['id'];
                    } else {
                        $newAsset = $this->assetService->addAsset('boat', $boat->id, $assetData);
                        if ($newAsset && isset($newAsset->id)) {
                            $payloadAssetIds[] = $newAsset->id;
                        }
                    }
                }

                // Hapus asset yang tidak ada di payload
                $existingAssets = $boat->assets()->pluck('id')->toArray();
                $assetsToDelete = array_diff($existingAssets, $payloadAssetIds);
                foreach ($assetsToDelete as $assetId) {
                    $this->assetService->deleteAsset($assetId);
                }
            }

            DB::commit();

            // Clear cache yang terkait
            $this->clearBoatCaches();

            return $boat->fresh(['cabin', 'assets']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating boat: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengha   pus boat berdasarkan ID.
     *
     * @param int $id
     * @return bool
     */
    public function deleteBoat($id)
    {
        $result = $this->boatRepository->deleteBoat($id);
        $this->clearBoatCaches();
        return $result;
    }

    /**
     * Menghapus semua cache boat
     *
     * @return void
     */
    public function clearBoatCaches()
    {
        Cache::forget(self::BOAT_ALL_CACHE_KEY);
        Cache::forget(self::BOAT_ACTIVE_CACHE_KEY);
        Cache::forget(self::BOAT_INACTIVE_CACHE_KEY);
    }

    public function updateBoatStatus($id, $status)
    {
        $boat = $this->getBoatById($id);

        if ($boat) {
            $result = $this->boatRepository->updateBoatStatus($id, $status);

            $this->clearBoatCaches($id);
        }
    }
}
