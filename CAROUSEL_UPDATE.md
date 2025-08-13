# Update Carousel - Penghapusan Field Link

## Overview

Field `link` pada tabel carousel sudah dihapus karena redundant dengan `file_url` di model Asset.

## Alasan Penghapusan

1. **Duplikasi Data**: Field `link` dan `file_url` di Asset memiliki fungsi yang sama
2. **Relasi Polymorphic**: Asset sudah menangani gambar carousel dengan lebih baik
3. **Struktur yang Lebih Bersih**: Satu tabel untuk satu jenis data

## Perubahan yang Dilakukan

### 1. Migration

```php
// database/migrations/2025_08_08_113856_remove_link_from_carousels_table.php
Schema::table('carousel', function (Blueprint $table) {
    $table->dropColumn('link');
});
```

### 2. Model Carousel

```php
// Sebelum
protected $fillable = [
    'title',
    'description',
    'link',        // ❌ Dihapus
    'order_num',
    'is_active',
];

// Sesudah
protected $fillable = [
    'title',
    'description',
    'order_num',
    'is_active',
];

// Ditambahkan method helper
public function getPrimaryImageAttribute()
{
    return $this->assets()->first();
}
```

### 3. Factory

```php
// Sebelum
return [
    'title' => $this->faker->sentence(3),
    'description' => $this->faker->paragraph,
    'link' => $this->faker->url,        // ❌ Dihapus
    'order_num' => $this->faker->numberBetween(1, 10),
    'is_active' => true,
];

// Sesudah
return [
    'title' => $this->faker->sentence(3),
    'description' => $this->faker->paragraph,
    'order_num' => $this->faker->numberBetween(1, 10),
    'is_active' => true,
];
```

## Cara Penggunaan Baru

### Sebelum (dengan field link)

```php
$carousel = Carousel::create([
    'title' => 'Promo Wisata',
    'description' => 'Deskripsi promo',
    'link' => 'https://example.com/promo',  // ❌ Tidak ada lagi
    'order_num' => 1,
    'is_active' => true,
]);
```

### Sesudah (dengan assets)

```php
// Buat carousel
$carousel = Carousel::create([
    'title' => 'Promo Wisata',
    'description' => 'Deskripsi promo',
    'order_num' => 1,
    'is_active' => true,
]);

// Tambah gambar melalui Asset
Asset::create([
    'assetable_id' => $carousel->id,
    'assetable_type' => get_class($carousel),
    'title' => 'Gambar Promo',
    'file_url' => 'https://source.unsplash.com/random/1200x600?boat,sea',
    'is_external' => true,
]);
```

### Atau gunakan Factory

```php
$carousel = Carousel::factory()->withAssets()->create();
```

## Akses Gambar

### Sebelum

```php
$imageUrl = $carousel->link;  // ❌ Tidak ada lagi
```

### Sesudah

```php
// Ambil semua gambar
$images = $carousel->assets;

// Ambil gambar pertama
$primaryImage = $carousel->primary_image;
$imageUrl = $primaryImage->file_url;

// Loop semua gambar
foreach ($carousel->assets as $asset) {
    echo $asset->file_url;
}
```

## Keuntungan Perubahan

✅ **Tidak ada duplikasi data**  
✅ **Struktur database lebih bersih**  
✅ **Relasi polymorphic yang konsisten**  
✅ **Mudah menambah multiple gambar**  
✅ **Gambar bisa dikategorikan dan diorganisir**

## Migration

Jalankan migration untuk menghapus field `link`:

```bash
php artisan migrate
```

## Rollback

Jika ingin kembali ke struktur lama:

```bash
php artisan migrate:rollback
```

## Testing

Test factory yang sudah diupdate:

```bash
php test_carousel_factory.php
```

## Catatan Penting

-   **Backup database** sebelum migration
-   **Update kode** yang masih menggunakan field `link`
-   **Test thoroughly** setelah perubahan
-   **Update dokumentasi** API jika ada
