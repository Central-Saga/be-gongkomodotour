# Integrasi Unsplash dengan Factory

## Overview

Factory ini sudah diintegrasikan dengan Unsplash untuk mendapatkan gambar acak yang relevan dengan tema wisata tanpa perlu API key.

## Cara Kerja

Factory menggunakan endpoint `https://source.unsplash.com/random/{width}x{height}?{keywords}` yang disediakan Unsplash secara gratis.

## Penggunaan Factory

### 1. Carousel Biasa (Tanpa Asset)

```php
use App\Models\Carousel;

$carousel = Carousel::factory()->create();
```

### 2. Carousel dengan 1 Asset (Gambar Unsplash)

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

## Tema Gambar yang Tersedia

Factory akan memilih secara acak dari keyword berikut:

-   `boat,sea` - Kapal dan laut
-   `beach,ocean` - Pantai dan samudra
-   `mountain,landscape` - Gunung dan pemandangan
-   `tropical,island` - Pulau tropis
-   `adventure,travel` - Petualangan dan perjalanan
-   `sunset,water` - Matahari terbenam dan air
-   `nature,forest` - Alam dan hutan
-   `cultural,heritage` - Budaya dan warisan

## Dimensi Gambar

Gambar yang dihasilkan memiliki dimensi 1200x600 pixel yang cocok untuk carousel.

## Keuntungan Menggunakan Unsplash

1. **Gratis** - Tidak perlu API key
2. **Kualitas Tinggi** - Gambar profesional
3. **Tema Relevan** - Sesuai dengan bisnis wisata
4. **Variasi** - Setiap kali generate akan berbeda
5. **Legal** - Gambar berlisensi gratis untuk komersial

## Alternatif Lain

Jika ingin kontrol lebih baik, bisa menggunakan Unsplash API resmi:

```php
// Perlu API key dari https://unsplash.com/developers
$unsplashUrl = "https://api.unsplash.com/photos/random?query=boat,sea&client_id=YOUR_API_KEY";
```

## Troubleshooting

-   Jika gambar tidak muncul, pastikan koneksi internet stabil
-   Unsplash mungkin memiliki rate limiting untuk source API
-   Untuk production, sebaiknya download dan simpan gambar lokal
