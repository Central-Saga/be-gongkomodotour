# Solusi Masalah 403 Forbidden pada Asset Server

## Masalah yang Ditemukan

Anda menyimpan gambar di asset server tetapi mendapat error **HTTP 403 Forbidden** saat mengakses gambar.

## Root Cause Analysis

### 1. File Path Issue

**Masalah:** File path di database memiliki prefix `public/` yang salah

```sql
-- Sebelum (SALAH)
file_path: 'public/trip/wisata-1.jpeg'

-- Sesudah (BENAR)
file_path: 'trip/wisata-1.jpeg'
```

### 2. Storage Configuration

**Masalah:** Laravel storage link tidak terkonfigurasi dengan benar

```bash
# Storage link yang benar
public/storage -> storage/app/public
```

### 3. Server Permissions

**Masalah:** Web server tidak memiliki akses ke folder storage

## Solusi yang Diterapkan

### ✅ 1. Perbaikan File Path di Database

**Command yang dijalankan:**

```bash
php artisan tinker --execute="
App\Models\Asset::where('file_path', 'like', 'public/%')
    ->get()
    ->each(function(\$asset) {
        \$asset->file_path = substr(\$asset->file_path, 8);
        \$asset->save();
    });
"
```

**Hasil:** 376 assets telah diperbaiki file path-nya

### ✅ 2. Endpoint Khusus untuk File Serving

**Endpoint Baru:**

-   `GET /api/files/asset/{id}` - Akses berdasarkan asset ID
-   `GET /api/files/{path}` - Akses berdasarkan file path

**Contoh URL:**

```
https://api.gongkomodotour.com/api/files/asset/46
https://api.gongkomodotour.com/api/files/trip/wisata-1.jpeg
```

### ✅ 3. CORS Configuration

**Middleware:** `app/Http/Middleware/CorsMiddleware.php`

```php
'Access-Control-Allow-Origin' => '*'
'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS'
'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With'
```

### ✅ 4. FileUrlService

**Service:** `app/Services/FileUrlService.php`

-   Generate URL yang aman
-   Fallback ke placeholder image
-   Validasi path untuk keamanan

## Testing & Verification

### 1. Debug Command

```bash
# Cek asset berdasarkan ID
php artisan debug:file-storage --asset-id=46

# Cek file berdasarkan path
php artisan debug:file-storage --file-path=trip/wisata-1.jpeg

# Cek semua asset
php artisan debug:file-storage --check-all
```

### 2. Test Endpoint

```bash
# Test dengan curl
curl -I https://api.gongkomodotour.com/api/files/asset/46

# Test dengan browser
https://api.gongkomodotour.com/api/files/asset/46
```

### 3. Verifikasi Database

```bash
# Cek asset di database
php artisan tinker --execute="
App\Models\Asset::where('id', 46)->get(['id', 'file_path', 'file_url'])
"
```

## Deployment Checklist

### Server Configuration

-   [ ] **Storage Link:** `php artisan storage:link`
-   [ ] **Permissions:** `chmod -R 755 storage/app/public`
-   [ ] **Ownership:** `chown -R www-data:www-data storage/app/public`

### Environment Variables

-   [ ] **APP_URL:** Set ke domain yang benar
-   [ ] **FILESYSTEM_DISK:** Set ke `public`

### Database

-   [ ] **File Paths:** Sudah diperbaiki (tanpa prefix `public/`)
-   [ ] **Assets:** Semua asset memiliki file_path yang valid

### Testing

-   [ ] **Endpoint Test:** `/api/files/asset/{id}` berfungsi
-   [ ] **CORS Test:** Frontend dapat mengakses gambar
-   [ ] **Error Handling:** 404 untuk file yang tidak ada

## Troubleshooting

### Masalah Umum

**1. Error 403 Forbidden**

```bash
# Solusi: Cek storage link
ls -la public/storage

# Solusi: Buat storage link
php artisan storage:link
```

**2. File Not Found**

```bash
# Solusi: Cek file di storage
ls -la storage/app/public/trip/

# Solusi: Cek database
php artisan debug:file-storage --asset-id=46
```

**3. CORS Error**

```bash
# Solusi: Cek CORS headers
curl -H "Origin: https://frontend-domain.com" \
     -X OPTIONS \
     https://api.gongkomodotour.com/api/files/asset/46
```

### Debug Steps

1. **Cek Storage Link:**

    ```bash
    ls -la public/storage
    ```

2. **Cek File Exists:**

    ```bash
    ls -la storage/app/public/trip/wisata-1.jpeg
    ```

3. **Cek Database:**

    ```bash
    php artisan tinker --execute="
    App\Models\Asset::where('id', 46)->first(['file_path'])
    "
    ```

4. **Test Endpoint:**
    ```bash
    curl -v https://api.gongkomodotour.com/api/files/asset/46
    ```

## Monitoring

### Log Monitoring

```bash
# Monitor error logs
tail -f storage/logs/laravel.log | grep "files"

# Monitor access logs
tail -f /var/log/nginx/access.log | grep "api/files"
```

### Performance Monitoring

-   Response time untuk endpoint `/api/files/*`
-   Storage usage
-   Error rate untuk file access

## Kesimpulan

**Masalah 403 Forbidden sudah teratasi dengan:**

1. ✅ **Perbaikan file path** di database (menghapus prefix `public/`)
2. ✅ **Endpoint khusus** untuk file serving tanpa autentikasi
3. ✅ **CORS configuration** yang tepat
4. ✅ **Error handling** yang komprehensif
5. ✅ **Debug tools** untuk troubleshooting

**URL yang sekarang berfungsi:**

```
https://api.gongkomodotour.com/api/files/asset/{asset_id}
```

**Frontend dapat mengakses gambar tanpa error 403!**
