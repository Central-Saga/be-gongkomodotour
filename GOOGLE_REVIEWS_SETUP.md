# Setup Google Reviews Integration

## Overview

Sistem testimonial telah diperbarui untuk menggabungkan testimonial internal dari database dengan review dari Google Places API untuk "Gong Komodo Tour".

## Perubahan Struktur Database

### Migration yang Dijalankan:

1. `2025_06_23_141849_update_testimonials_table_remove_customer_id.php`

    - Menghapus kolom `customer_id`
    - Menambahkan kolom baru:
        - `customer_name` (string)
        - `customer_email` (string, nullable)
        - `customer_phone` (string, nullable)
        - `source` (string, default: 'internal')

2. `2025_06_23_142851_make_trip_id_nullable_in_testimonials.php`
    - Membuat `trip_id` nullable
    - Menghapus `google_review_id` (tidak diperlukan)

## Konfigurasi Environment Variables

Tambahkan variabel berikut ke file `.env`:

```env
# Google Places API Configuration
GOOGLE_PLACES_API_KEY=your_google_places_api_key_here
GOOGLE_PLACE_ID=your_place_id_for_gong_komodo_tour
```

### Cara Mendapatkan Google Places API Key:

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang ada
3. Aktifkan Places API
4. Buat credentials (API Key)
5. Batasi API Key untuk Places API saja

### Cara Mendapatkan Place ID:

1. Buka [Google Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id)
2. Cari "Gong Komodo Tour"
3. Salin Place ID yang muncul

## API Endpoints Baru

### 1. Semua Testimonial (Internal + Google Reviews)

```
GET /api/landing-page/all-testimonials
```

Query Parameters:

-   `google_limit` (optional, default: 5) - Jumlah Google reviews yang diambil
-   `internal_limit` (optional, default: 10) - Jumlah testimonial internal yang diambil

Response:

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "author_name": "John Doe",
            "rating": 5,
            "text": "Amazing experience!",
            "time": 1640995200,
            "profile_photo_url": null,
            "source": "internal",
            "trip": {
                "id": 1,
                "name": "Bali Cultural Journey"
            },
            "created_at": "2025-06-23T14:22:34.000000Z",
            "updated_at": "2025-06-23T14:22:34.000000Z"
        },
        {
            "author_name": "Jane Smith",
            "rating": 5,
            "text": "Great service!",
            "time": 1640995200,
            "profile_photo_url": "https://...",
            "source": "google_review",
            "created_at": "2025-06-23T14:22:34.000000Z",
            "updated_at": "2025-06-23T14:22:34.000000Z"
        }
    ],
    "meta": {
        "total": 15,
        "google_count": 5,
        "internal_count": 10
    }
}
```

### 2. Testimonial yang Di-highlight

```
GET /api/landing-page/highlighted-testimonials
```

Query Parameters:

-   `limit` (optional, default: 5) - Jumlah testimonial yang diambil

### 3. Google Reviews Saja

```
GET /api/landing-page/google-reviews
```

Query Parameters:

-   `limit` (optional, default: 5) - Jumlah review yang diambil

## Fitur Cache

-   Google Reviews di-cache selama 1 jam untuk mengurangi API calls
-   Cache key: `google_reviews_{limit}`
-   Cache dapat di-clear dengan: `php artisan cache:clear`

## Service Class

### GooglePlacesService

File: `app/Services/GooglePlacesService.php`

Methods:

-   `getLatestReviews(int $limit = 5)` - Ambil review terbaru dari Google
-   `getAllTestimonials(int $googleLimit = 5, int $internalLimit = 10)` - Gabungkan testimonial internal + Google reviews
-   `getHighlightedTestimonials(int $limit = 5)` - Ambil testimonial yang di-highlight

## Error Handling

-   Jika Google Places API gagal, sistem akan mengembalikan testimonial internal saja
-   Error akan di-log ke Laravel log
-   Cache akan mencegah error berulang dalam 1 jam

## Testing

Untuk testing tanpa Google API:

1. Set `GOOGLE_PLACES_API_KEY` ke nilai dummy
2. Service akan mengembalikan array kosong untuk Google reviews
3. Testimonial internal tetap berfungsi normal

## Migration Commands

```bash
# Jalankan migration
php artisan migrate

# Rollback jika diperlukan
php artisan migrate:rollback --step=2

# Seed data testimonial
php artisan db:seed --class=TestimonialSeeder
```
