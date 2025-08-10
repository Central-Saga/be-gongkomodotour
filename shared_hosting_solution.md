# Solusi Shared Hosting - Tidak Bisa Storage Link

## Masalah
- Shared hosting tidak mengizinkan symbolic links
- `php artisan storage:link` gagal
- Error 403 saat akses gambar

## Solusi untuk Shared Hosting

### 1. Copy Storage ke Public (Manual)
```bash
# Di server shared hosting
cd /home/gongkomo/api.gongkomodotour.com

# Copy folder storage/app/public ke public/storage
cp -r storage/app/public public/storage

# Set permissions
chmod -R 755 public/storage
```

### 2. Update FileUrlService untuk Shared Hosting
```php
// Di app/Services/FileUrlService.php
public static function generateAssetUrl(Asset $asset): string
{
    // Jika asset eksternal, gunakan URL eksternal
    if ($asset->is_external) {
        return $asset->file_url;
    }

    // Jika file_path tidak ada, gunakan URL default
    if (!$asset->file_path) {
        return self::getDefaultImageUrl();
    }

    // Cek apakah file ada di storage
    if (!Storage::disk('public')->exists($asset->file_path)) {
        return self::getDefaultImageUrl();
    }

    // Untuk shared hosting, gunakan URL langsung ke public/storage
    return url('/storage/' . $asset->file_path);
}
```

### 3. Update FileController untuk Shared Hosting
```php
// Di app/Http/Controllers/FileController.php
public function serveAsset(string $id)
{
    try {
        $asset = Asset::findOrFail($id);

        // Jika asset eksternal, redirect ke URL eksternal
        if ($asset->is_external) {
            return redirect($asset->file_url);
        }

        // Jika file_path tidak ada
        if (!$asset->file_path) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tidak ditemukan'
            ], 404);
        }

        // Validasi path untuk keamanan
        $filePath = $asset->file_path;
        if (str_contains($filePath, '..') || str_contains($filePath, '//')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Path file tidak valid'
            ], 400);
        }

        // Cek apakah file ada di public/storage (untuk shared hosting)
        $publicPath = public_path('storage/' . $filePath);
        if (!file_exists($publicPath)) {
            return response()->json([
                'status' => 'error',
                'message' => 'File tidak ditemukan di storage'
            ], 404);
        }

        // Return file dengan header yang tepat
        $mimeType = mime_content_type($publicPath);
        $file = file_get_contents($publicPath);

        return Response::make($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            'Cache-Control' => 'public, max-age=31536000', // Cache selama 1 tahun
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat mengakses file'
        ], 500);
    }
}
```

### 4. Langkah-langkah Deployment di Shared Hosting

```bash
# 1. Upload vendor folder (jika belum)
# Extract vendor.tar.gz

# 2. Clear cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# 3. Copy storage ke public (karena tidak bisa symbolic link)
cp -r storage/app/public public/storage

# 4. Set permissions
chmod -R 755 public/storage
chmod -R 755 storage/app/public

# 5. Fix file paths di database
php artisan tinker --execute="
App\Models\Asset::where('file_path', 'like', 'public/%')
    ->get()
    ->each(function(\$asset) {
        \$asset->file_path = substr(\$asset->file_path, 8);
        \$asset->save();
    });
echo 'Fixed assets with public/ prefix';
"

# 6. Test
curl -I https://api.gongkomodotour.com/
curl -I https://api.gongkomodotour.com/api/files/asset/46
```

### 5. Alternative: Gunakan URL Langsung

Jika masih bermasalah, gunakan URL langsung ke public/storage:

```php
// Di AssetResource
'file_url' => url('/storage/' . $this->file_path),
```

### 6. Cek File Structure
```bash
# Pastikan struktur folder benar
ls -la public/storage/
ls -la storage/app/public/

# File seharusnya ada di kedua lokasi
ls -la public/storage/trip/
ls -la storage/app/public/trip/
```

## Testing

### 1. Test URL Langsung
```
https://api.gongkomodotour.com/storage/trip/wisata-1.jpeg
```

### 2. Test API Endpoint
```
https://api.gongkomodotour.com/api/files/asset/46
```

### 3. Debug Command
```bash
php artisan debug:file-storage --asset-id=46
```

## Troubleshooting

### Error 403 pada /storage/
- Pastikan folder `public/storage` ada
- Pastikan permissions benar (755)
- Pastikan file benar-benar ada di folder tersebut

### Error 404 pada API endpoint
- Pastikan vendor folder sudah diupload
- Pastikan cache sudah di-clear
- Pastikan file paths di database sudah benar

## Kesimpulan

Untuk shared hosting:
1. **Copy** storage ke public (bukan symbolic link)
2. **Update** FileUrlService untuk gunakan URL langsung
3. **Set** permissions yang benar
4. **Test** dengan URL langsung ke /storage/
