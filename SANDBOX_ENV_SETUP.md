# Setup Environment Variables untuk Sandbox

Dokumentasi ini menjelaskan environment variables yang **wajib** di-set di server API sandbox (`sandbox.api.gongkomodotour.com`) agar Sanctum cookie-based authentication berfungsi dengan frontend sandbox (`sandbox.gongkomodotour.com`).

## Environment Variables Wajib

Edit file `.env` di server `sandbox.api.gongkomodotour.com` dan pastikan variabel berikut sudah di-set:

```env
# URL aplikasi backend
APP_URL=https://sandbox.api.gongkomodotour.com

# Domain frontend yang diizinkan untuk stateful authentication (tanpa https://, hanya hostname)
# PENTING: Jika ada multiple domain, pisahkan dengan koma
SANCTUM_STATEFUL_DOMAINS=sandbox.gongkomodotour.com

# URL frontend (opsional, tapi disarankan untuk konsistensi)
FRONTEND_URL=https://sandbox.gongkomodotour.com

# Session driver harus cookie untuk Sanctum stateful auth
SESSION_DRIVER=cookie

# Domain cookie - PENTING: harus diawali titik (.) agar berlaku untuk semua subdomain
SESSION_DOMAIN=.gongkomodotour.com

# Cookie harus secure karena menggunakan HTTPS
SESSION_SECURE_COOKIE=true
```

## Penjelasan Detail

### `APP_URL`
- **Nilai**: `https://sandbox.api.gongkomodotour.com`
- **Deskripsi**: URL lengkap dari API backend sandbox
- **Penting**: Harus menggunakan protokol HTTPS

### `SANCTUM_STATEFUL_DOMAINS`
- **Nilai**: `sandbox.gongkomodotour.com`
- **Deskripsi**: Domain frontend yang diizinkan untuk melakukan stateful authentication (cookie-based)
- **PENTING**: 
  - Hanya hostname, **tanpa** `https://` atau `http://`
  - Jika ada multiple domain, pisahkan dengan koma: `sandbox.gongkomodotour.com,www.sandbox.gongkomodotour.com`
  - Sanctum akan memeriksa `Origin` header dari request dan membandingkannya dengan daftar ini

### `FRONTEND_URL`
- **Nilai**: `https://sandbox.gongkomodotour.com`
- **Deskripsi**: URL lengkap frontend (opsional, digunakan untuk referensi)
- **Catatan**: Beberapa konfigurasi Laravel menggunakan ini untuk auto-generate URLs

### `SESSION_DRIVER`
- **Nilai**: `cookie`
- **Deskripsi**: Driver session yang digunakan. Untuk Sanctum stateful auth, harus menggunakan `cookie`
- **Alternatif**: Bisa juga `database` atau `redis`, tapi `cookie` paling sederhana untuk cross-subdomain

### `SESSION_DOMAIN`
- **Nilai**: `.gongkomodotour.com`
- **Deskripsi**: Domain di mana cookie session akan berlaku
- **PENTING**: 
  - **Harus diawali titik (.)** agar cookie berlaku untuk semua subdomain
  - Tanpa titik, cookie hanya berlaku untuk domain exact match
  - Contoh: `.gongkomodotour.com` akan berlaku untuk:
    - `sandbox.gongkomodotour.com`
    - `api.gongkomodotour.com`
    - `gongkomodotour.com`
    - `www.gongkomodotour.com`

### `SESSION_SECURE_COOKIE`
- **Nilai**: `true`
- **Deskripsi**: Cookie hanya dikirim melalui HTTPS
- **Penting**: Harus `true` untuk production/sandbox yang menggunakan HTTPS

## Setelah Mengubah `.env`

Setelah mengubah file `.env`, **wajib** jalankan perintah berikut di server sandbox:

```bash
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
```

**Catatan**: Jika menggunakan PHP-FPM, mungkin perlu restart PHP-FPM agar perubahan ter-load:

```bash
# Contoh untuk systemd
sudo systemctl restart php-fpm

# Atau untuk service tertentu
sudo service php8.x-fpm restart
```

## Verifikasi Konfigurasi

Setelah setup, lakukan verifikasi dengan curl:

### 1. Test CSRF Cookie Endpoint

```bash
curl -i https://sandbox.api.gongkomodotour.com/sanctum/csrf-cookie
```

**Output yang diharapkan**:
- Header `Set-Cookie` dengan `XSRF-TOKEN` yang memiliki `domain=.gongkomodotour.com; secure; SameSite=None`
- Header `Set-Cookie` dengan `laravel_session` yang memiliki `domain=.gongkomodotour.com; secure; SameSite=None`

### 2. Test CORS Preflight

```bash
curl -i -H "Origin: https://sandbox.gongkomodotour.com" \
     -H "Access-Control-Request-Method: GET" \
     -X OPTIONS \
     https://sandbox.api.gongkomodotour.com/sanctum/csrf-cookie
```

**Output yang diharapkan**:
- `Access-Control-Allow-Origin: https://sandbox.gongkomodotour.com` (bukan `*`)
- `Access-Control-Allow-Credentials: true`
- `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`

## Troubleshooting

### Masalah: Cookie tidak ter-set di browser

**Kemungkinan penyebab**:
1. `SESSION_DOMAIN` tidak diawali titik → ubah ke `.gongkomodotour.com`
2. Config cache belum di-clear → jalankan `php artisan config:clear`
3. Browser memblok cookie karena SameSite policy → pastikan `SESSION_SAME_SITE=none` di `.env` (sudah default di `config/session.php`)

### Masalah: CSRF token mismatch masih terjadi

**Kemungkinan penyebab**:
1. `SANCTUM_STATEFUL_DOMAINS` tidak match dengan origin request → pastikan hostname exact match (tanpa https://)
2. Cookie tidak terkirim karena CORS → pastikan `allowed_origins` di `config/cors.php` sudah benar
3. Reverse proxy (Nginx/Cloudflare) yang strip cookie headers → cek response headers dari server

### Masalah: CORS error di browser

**Kemungkinan penyebab**:
1. `allowed_origins` masih `['*']` di `config/cors.php` → ubah ke array spesifik
2. `supports_credentials: true` tapi `allowed_origins: ['*']` → browser akan menolak kombinasi ini
3. Origin tidak ada di `allowed_origins` → tambahkan origin frontend ke array

## Konfigurasi Tambahan (Opsional)

Jika ingin menambahkan domain lain (misalnya production atau staging), tambahkan ke:

1. **`.env`**:
   ```env
   SANCTUM_STATEFUL_DOMAINS=sandbox.gongkomodotour.com,gongkomodotour.com,www.gongkomodotour.com
   ```

2. **`config/cors.php`**:
   ```php
   'allowed_origins' => [
       'https://sandbox.gongkomodotour.com',
       'https://gongkomodotour.com',
       'https://www.gongkomodotour.com',
   ],
   ```

## Referensi

- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)
- [Laravel CORS Configuration](https://laravel.com/docs/cors)
- [MDN: HTTP Cookies](https://developer.mozilla.org/en-US/docs/Web/HTTP/Cookies)
- [MDN: CORS](https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS)
