# Complete Update Guide - Carousel Field Link Removal

## Overview

Field `link` pada tabel carousel sudah dihapus karena redundant dengan `file_url` di model Asset. Semua layer (Repository, Service, Request, Resource, Controller) sudah diupdate.

## Perubahan yang Sudah Dibuat

### 1. Database Migration

```bash
# Jalankan migration untuk menghapus field link
php artisan migrate
```

**File**: `database/migrations/2025_08_08_113856_remove_link_from_carousels_table.php`

### 2. Model Updates

#### Carousel Model

-   ✅ Hapus field `link` dari `$fillable`
-   ✅ Tambah method `getPrimaryImageAttribute()`
-   ✅ Relasi `assets()` sudah ada

#### Asset Model

-   ✅ Hapus method `carousel()` yang duplikat
-   ✅ Relasi `assetable()` sudah benar

### 3. Factory Updates

#### CarouselFactory

-   ✅ Hapus field `link`
-   ✅ Method `withAssets()` untuk create assets dengan gambar Unsplash
-   ✅ Generate Unsplash URL dengan tema wisata

#### AssetFactory

-   ✅ Tambah method `forCarousel()`
-   ✅ Support untuk Carousel di `$assetTypes`

### 4. Seeder Updates

#### CarouselSeeder

-   ✅ Gunakan `withAssets(2)` untuk create 5 carousel dengan masing-masing 2 asset
-   ✅ Assets akan ter-create dengan gambar Unsplash

### 5. Request Updates

#### CarouselStoreRequest

-   ✅ Hapus field `link`
-   ✅ Tambah validation untuk `assets` array
-   ✅ Support untuk multiple assets

#### CarouselUpdateRequest

-   ✅ Hapus field `link`
-   ✅ Tambah validation untuk `assets` array
-   ✅ Support untuk multiple assets

### 6. Resource Updates

#### CarouselResource

-   ✅ Hapus field `link`
-   ✅ Tambah field `primary_image`
-   ✅ Assets selalu di-load

### 7. Repository Updates

#### CarouselRepositoryInterface

-   ✅ Tambah method `getWithAssetsCount()`
-   ✅ Tambah method `getByOrder()`

#### CarouselRepository

-   ✅ Semua method load `assets` dengan `with('assets')`
-   ✅ Tambah method baru
-   ✅ Handle assets deletion saat delete carousel

### 8. Service Updates

#### CarouselServiceInterface

-   ✅ Tambah method `getCarouselWithAssetsCount()`
-   ✅ Tambah method `getCarouselByOrder()`

#### CarouselService

-   ✅ Handle assets creation saat create carousel
-   ✅ Handle assets update saat update carousel
-   ✅ Handle assets deletion saat delete carousel
-   ✅ Semua method load `assets`

### 9. Controller Updates

#### CarouselController

-   ✅ Support query parameter `order` dan `assets_count`
-   ✅ Tambah method `withAssetsCount()`
-   ✅ Tambah method `ordered()`

## Cara Penggunaan Baru

### 1. Seeding dengan Assets

```bash
# Jalankan seeder
php artisan db:seed --class=CarouselSeeder

# Atau jalankan semua seeder
php artisan db:seed
```

### 2. API Endpoints

#### Get All Carousels

```bash
GET /api/carousels
```

#### Get Carousels with Assets Count

```bash
GET /api/carousels?assets_count=2
```

#### Get Carousels Ordered

```bash
GET /api/carousels?order=true
```

#### Get Carousels by Status

```bash
GET /api/carousels?status=1  # Active
GET /api/carousels?status=0  # Inactive
```

### 3. Create Carousel dengan Assets

```json
POST /api/carousels
{
    "title": "Promo Wisata",
    "description": "Deskripsi promo",
    "order_num": 1,
    "is_active": true,
    "assets": [
        {
            "title": "Gambar 1",
            "description": "Deskripsi gambar 1",
            "file_url": "https://source.unsplash.com/random/1200x600?boat,sea",
            "is_external": true
        },
        {
            "title": "Gambar 2",
            "description": "Deskripsi gambar 2",
            "file_url": "https://source.unsplash.com/random/1200x600?beach,ocean",
            "is_external": true
        }
    ]
}
```

### 4. Update Carousel dengan Assets

```json
PUT /api/carousels/{id}
{
    "title": "Promo Wisata Updated",
    "assets": [
        {
            "title": "Gambar Baru",
            "file_url": "https://source.unsplash.com/random/1200x600?mountain,landscape",
            "is_external": true
        }
    ]
}
```

## Testing

### 1. Test Factory

```bash
php test_carousel_factory.php
```

### 2. Test Seeder

```bash
php artisan db:seed --class=CarouselSeeder
```

### 3. Test API

```bash
# Test get carousels
curl http://localhost:8000/api/carousels

# Test get carousels with assets count
curl "http://localhost:8000/api/carousels?assets_count=2"

# Test get carousels ordered
curl "http://localhost:8000/api/carousels?order=true"
```

## Troubleshooting

### 1. Assets Tidak Ter-Create

-   ✅ Pastikan seeder menggunakan `withAssets()`
-   ✅ Check database connection
-   ✅ Check Asset model fillable fields

### 2. Field Link Masih Ada

-   ✅ Jalankan migration: `php artisan migrate`
-   ✅ Check migration file exists
-   ✅ Check database schema

### 3. API Error

-   ✅ Check validation rules
-   ✅ Check required fields
-   ✅ Check database constraints

## Migration Commands

```bash
# Jalankan migration
php artisan migrate

# Rollback jika ada masalah
php artisan migrate:rollback

# Check migration status
php artisan migrate:status

# Reset database dan jalankan semua seeder
php artisan migrate:fresh --seed
```

## File yang Sudah Diupdate

1. ✅ `database/migrations/2025_08_08_113856_remove_link_from_carousels_table.php`
2. ✅ `app/Models/Carousel.php`
3. ✅ `app/Models/Asset.php`
4. ✅ `database/factories/CarouselFactory.php`
5. ✅ `database/factories/AssetFactory.php`
6. ✅ `database/seeders/CarouselSeeder.php`
7. ✅ `app/Http/Requests/CarouselStoreRequest.php`
8. ✅ `app/Http/Requests/CarouselUpdateRequest.php`
9. ✅ `app/Http/Resources/CarouselResource.php`
10. ✅ `app/Repositories/Contracts/CarouselRepositoryInterface.php`
11. ✅ `app/Repositories/Eloquent/CarouselRepository.php`
12. ✅ `app/Services/Contracts/CarouselServiceInterface.php`
13. ✅ `app/Services/Implementations/CarouselService.php`
14. ✅ `app/Http/Controllers/CarouselController.php`

## Keuntungan Perubahan

✅ **Tidak ada duplikasi data**  
✅ **Struktur database lebih bersih**  
✅ **Relasi polymorphic yang konsisten**  
✅ **Support multiple assets per carousel**  
✅ **Gambar bisa dikategorikan dan diorganisir**  
✅ **API lebih fleksibel**  
✅ **Validation yang lebih baik**

## Catatan Penting

-   **Backup database** sebelum migration
-   **Test thoroughly** setelah perubahan
-   **Update frontend** jika ada yang menggunakan field `link`
-   **Check API documentation** jika ada
