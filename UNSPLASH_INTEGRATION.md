# Integrasi Gambar Wisata dengan Factory

## Overview

Factory ini sudah diintegrasikan dengan **multiple image sources** untuk mendapatkan gambar acak yang relevan dengan tema wisata tanpa perlu API key.

**Update Terbaru**: Field `link` sudah dihapus dari carousel karena redundant dengan `file_url` di Asset.

**Update Gambar**: Sekarang menggunakan multiple image sources yang lebih reliable (Picsum, Placeholder, Unsplash ID).

**Update API**: Sekarang menggunakan Unsplash API yang proper dengan API key untuk gambar berkualitas tinggi.

## Cara Kerja

Factory menggunakan **multiple image sources** yang reliable:

1. **Unsplash API** - Paling reliable, berkualitas tinggi, dengan API key
2. **Picsum Photos** - Reliable, gratis, random images
3. **Placeholder.com** - Customizable, gratis, dengan text
4. **Unsplash ID Spesifik** - Reliable, gratis, gambar tetap
5. **Source Unsplash** - Mungkin bermasalah, sebagai fallback

## Konfigurasi Unsplash API

### 1. Set API Key di .env

```bash
UNSPLASH_ACCESS_KEY=your_unsplash_api_key_here
```

### 2. API Key sudah dikonfigurasi di config/services.php

```php
'unsplash' => [
    'key' => env('UNSPLASH_ACCESS_KEY'),
],
```

### 3. Dapatkan API Key dari Unsplash

1. Daftar di [https://unsplash.com/developers](https://unsplash.com/developers)
2. Buat aplikasi baru
3. Copy Access Key
4. Paste ke file .env

## Penggunaan Factory

### 1. Carousel Biasa (Tanpa Asset)

```php
use App\Models\Carousel;

$carousel = Carousel::factory()->create();
```

### 2. Carousel dengan 1 Asset (Gambar Reliable)

```php
$carousel = Carousel::factory()->withAssets()->create();
```

### 3. Carousel dengan Multiple Assets

```php
$carousel = Carousel::factory()->withAssets(3)->create();
```

### 4. Dalam Seeder

```php
// database/seeders/CarouselSeeder.php
public function run(): void
{
    // Buat 5 carousel dengan masing-masing 2 asset
    Carousel::factory(5)->withAssets(2)->create();
}
```

## Field yang Tersedia

### Carousel Table

-   `title` - Judul carousel
-   `description` - Deskripsi carousel
-   `order_num` - Urutan tampilan
-   `is_active` - Status aktif

**Note**: Field `link` sudah dihapus karena gambar sekarang dihandle oleh Asset.

### Asset Table (melalui relasi)

-   `title` - Judul asset
-   `description` - Deskripsi asset
-   `file_path` - Path file (URL gambar)
-   `file_url` - URL file (URL gambar) - **Ini yang menggantikan field link**
-   `is_external` - Status external URL

## Tema Gambar yang Tersedia

Factory akan memilih secara acak dari keyword berikut:

-   `boat` - Kapal
-   `sea` - Laut
-   `beach` - Pantai
-   `ocean` - Samudra
-   `mountain` - Gunung
-   `landscape` - Pemandangan
-   `tropical` - Tropis
-   `island` - Pulau
-   `adventure` - Petualangan
-   `travel` - Perjalanan
-   `sunset` - Matahari terbenam
-   `water` - Air
-   `nature` - Alam
-   `forest` - Hutan
-   `cultural` - Budaya
-   `heritage` - Warisan
-   `vacation` - Liburan
-   `holiday` - Hari libur
-   `destination` - Destinasi
-   `paradise` - Surga

## Dimensi Gambar

Factory akan memilih secara acak dari dimensi berikut:

-   `1200x600` - Landscape untuk carousel
-   `1920x1080` - Full HD
-   `1600x900` - HD
-   `800x600` - Small landscape
-   `600x800` - Portrait

## Image Sources yang Digunakan

### 1. Unsplash API (Paling Reliable dengan API Key)

```
https://api.unsplash.com/photos/random?query=boat&orientation=landscape&w=1200&h=600&fit=crop&client_id=YOUR_API_KEY
```

**Keuntungan:**

-   ✅ Sangat reliable
-   ✅ Berkualitas tinggi
-   ✅ Random images setiap kali
-   ✅ Dimensi customizable
-   ✅ Proper attribution
-   ✅ Rate limit yang reasonable

**Cara Kerja:**

1. Factory generate API URL dengan query
2. Service call API dan dapat response
3. Extract URL gambar dari response
4. Return URL gambar yang bisa langsung ditampilkan

### 2. Picsum Photos (Fallback, Reliable, Gratis)

```
https://picsum.photos/1200/600?random=1
https://picsum.photos/1200/600?random=100
https://picsum.photos/1200/600?random=500
```

**Keuntungan:**

-   ✅ Sangat reliable
-   ✅ Gratis
-   ✅ Random images setiap kali
-   ✅ Dimensi customizable

### 3. Placeholder.com (Customizable, Gratis)

```
https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat
https://via.placeholder.com/1200x600/00cc66/ffffff?text=Sea
https://via.placeholder.com/1200x600/ffcc00/000000?text=Beach
```

**Keuntungan:**

-   ✅ Sangat reliable
-   ✅ Gratis
-   ✅ Customizable colors dan text
-   ✅ Dimensi customizable

### 4. Unsplash dengan ID Spesifik (Reliable, Gratis)

```
https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop
https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1200&h=600&fit=crop
```

**Keuntungan:**

-   ✅ Reliable
-   ✅ Gratis
-   ✅ Gambar tetap (tidak berubah)
-   ✅ Kualitas tinggi

### 5. Source Unsplash (Fallback, Mungkin Bermasalah)

```
https://source.unsplash.com/random/1200x600/?boat
https://source.unsplash.com/random/1200x600/?sea,travel
```

**Keuntungan:**

-   ✅ Gratis
-   ✅ Random images
-   ⚠️ Mungkin bermasalah

## Keuntungan Menggunakan Multiple Sources

1. **Reliability** - Jika satu source bermasalah, ada alternatif lain
2. **Gratis** - Tidak perlu API key untuk fallback
3. **Kualitas Tinggi** - Gambar profesional dari Unsplash API
4. **Tema Relevan** - Sesuai dengan bisnis wisata
5. **Variasi** - Setiap kali generate akan berbeda
6. **Legal** - Gambar berlisensi gratis untuk komersial
7. **Fallback** - Selalu ada gambar yang bisa ditampilkan
8. **Proper Attribution** - Credit photographer yang proper

## Alternatif Lain

Jika ingin kontrol lebih baik, bisa menggunakan Unsplash API resmi:

```php
// Perlu API key dari https://unsplash.com/developers
$unsplashService = app(\App\Services\UnsplashService::class);
$photo = $unsplashService->getRandomPhoto('boat,sea');
$imageUrl = $photo['url'] ?? null;
```

## Troubleshooting

### Assets Tidak Ter-Create

Jika assets tidak ter-create, coba cara berikut:

1. **Pastikan relasi polymorphic benar:**

```php
// Di model Carousel
public function assets()
{
    return $this->morphMany(Asset::class, 'assetable');
}
```

2. **Buat asset manual untuk testing:**

```php
$carousel = Carousel::factory()->create();
Asset::create([
    'assetable_id' => $carousel->id,
    'assetable_type' => get_class($carousel),
    'title' => 'Test Asset',
    'file_path' => 'https://picsum.photos/1200/600?random=1',
    'file_url' => 'https://picsum.photos/1200/600?random=1',
    'is_external' => true,
]);
```

3. **Gunakan AssetFactory dengan method forCarousel:**

```php
$carousel = Carousel::factory()->create();
Asset::factory()->forCarousel($carousel)->create();
```

### Gambar Tidak Tampil

Jika gambar tidak tampil, coba:

1. **Test URL accessibility:**

```bash
php test_unsplash_access.php
```

2. **Test travel images:**

```bash
php test_travel_images.php
```

3. **Test Unsplash API:**

```bash
php test_unsplash_api.php
```

4. **Check browser console** untuk error

5. **Gunakan URL yang reliable:**

```php
// Picsum (paling reliable)
"https://picsum.photos/1200/600?random=1"

// Placeholder (customizable)
"https://via.placeholder.com/1200x600/0066cc/ffffff?text=Boat"

// Unsplash ID spesifik
"https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200&h=600&fit=crop"
```

### URL Too Long Error

Jika mendapat error `Data too long for column 'file_url'`:

1. **Jalankan migration untuk update kolom:**

```bash
php artisan migrate
```

2. **Migration akan mengubah:**

-   `file_url` dari `string(255)` menjadi `text`
-   `file_path` dari `string(255)` menjadi `text`

3. **Test URL length:**

```bash
php test_url_length.php
```

4. **Solusi alternatif:**

-   Factory sekarang return URL gambar langsung, bukan API URL
-   Gunakan fallback services jika API bermasalah
-   URL yang dihasilkan sekarang lebih pendek

### Unsplash API Tidak Berfungsi

Jika Unsplash API tidak berfungsi:

1. **Check API key:**

```bash
php test_unsplash_api.php
```

2. **Check .env file:**

```bash
UNSPLASH_ACCESS_KEY=your_api_key_here
```

3. **Check config/services.php:**

```php
'unsplash' => [
    'key' => env('UNSPLASH_ACCESS_KEY'),
],
```

4. **Check rate limit:**

-   Free tier: 50 requests per hour
-   Paid tier: 5000 requests per hour

5. **Gunakan fallback services** sampai API key dikonfigurasi

### Field Link vs Assets

-   **Field `link` sudah dihapus** - Sekarang gambar dihandle oleh Asset
-   **Assets** - Gambar yang ditampilkan di carousel melalui `file_url`
-   **Lebih efisien** - Tidak ada duplikasi data

### Test Factory

Jalankan file test untuk memastikan factory berjalan:

```bash
php test_carousel_factory.php
```

### Test Gambar

Test gambar yang baru:

```bash
php test_travel_images.php
```

### Test Unsplash API

Test Unsplash API:

```bash
php test_unsplash_api.php
```

### Test URL Length

Test URL length dan database constraints:

```bash
php test_url_length.php
```

## Migration

Untuk menghapus field `link`, jalankan:

```bash
php artisan migrate
```

Migration akan menghapus field `link` dari tabel `carousel` karena sudah tidak diperlukan.

## Troubleshooting

-   Jika gambar tidak muncul, pastikan koneksi internet stabil
-   Test URL di browser untuk memastikan accessible
-   Gunakan Picsum Photos sebagai fallback (paling reliable)
-   Untuk production, sebaiknya download dan simpan gambar lokal
-   Pastikan menggunakan multiple image sources untuk reliability
-   Check Unsplash API key dan rate limit
-   Gunakan fallback services jika API bermasalah
