# Rate Limiter & Auto Logout Implementation

## ✅ Implementasi yang Sudah Dilakukan

### 1. **Rate Limiter untuk Login**

**File**: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

-   ✅ Aktifkan `$request->authenticate()` untuk menggunakan rate limiter built-in
-   ✅ Rate limiter akan membatasi maksimal 5 percobaan login gagal per IP
-   ✅ Setelah 5 percobaan gagal, user akan diblokir sementara
-   ✅ Rate limiter akan di-clear otomatis setelah login berhasil

### 2. **Throttle Middleware di Route API**

**File**: `routes/api.php`

-   ✅ Login: 5 percobaan per menit
-   ✅ Register: 3 percobaan per menit
-   ✅ Logout: 10 percobaan per menit
-   ✅ Perbaikan syntax middleware array

### 3. **Konfigurasi Session & Token**

**File**: `config/session.php`

-   ✅ Session lifetime: 120 menit (2 jam)
-   ✅ Session tidak expire saat browser ditutup

**File**: `config/sanctum.php`

-   ✅ Token expiration: null (tidak expire otomatis)
-   ✅ Bisa diubah ke menit tertentu jika diperlukan

### 4. **Auto Logout untuk User Tidak Aktif**

**File**: `app/Http/Middleware/CheckUserStatus.php`

-   ✅ Sudah ada dan berfungsi
-   ✅ Auto logout jika status user bukan 'Aktif'
-   ✅ Auto delete token saat user tidak aktif

## 🔧 Cara Kerja Rate Limiter

### Login Rate Limiting:

1. **Percobaan 1-5**: Login normal
2. **Percobaan 6+**: Diblokir sementara (default 1 menit)
3. **Setelah berhasil login**: Rate limit di-clear

### Throttle Middleware:

-   **Login**: Maksimal 5 request per menit per IP
-   **Register**: Maksimal 3 request per menit per IP
-   **Logout**: Maksimal 10 request per menit per IP

## 📝 Pesan Error yang Akan Muncul

### Rate Limit Exceeded:

```json
{
    "message": "Too many login attempts. Please try again in X seconds.",
    "errors": {
        "email": ["Too many login attempts. Please try again in X seconds."]
    }
}
```

### User Tidak Aktif:

```json
{
    "message": "Akun anda tidak aktif."
}
```

## ⚙️ Konfigurasi Tambahan (Opsional)

### Ubah Session Lifetime:

```php
// di config/session.php
'lifetime' => (int) env('SESSION_LIFETIME', 480), // 8 jam
```

### Ubah Token Expiration:

```php
// di config/sanctum.php
'expiration' => 60, // 60 menit
```

### Ubah Rate Limit:

```php
// di app/Http/Requests/Auth/LoginRequest.php
if (! RateLimiter::tooManyAttempts($this->throttleKey(), 10)) { // ubah dari 5 ke 10
```

## 🧪 Testing

### Test Rate Limiter:

1. Coba login dengan password salah 6 kali berturut-turut
2. Sistem akan memblokir IP selama 1 menit
3. Setelah 1 menit, coba login lagi

### Test Auto Logout:

1. Login dengan user yang statusnya 'Aktif'
2. Ubah status user ke 'Tidak Aktif' di database
3. Lakukan request API apapun
4. Sistem akan auto logout dan return 403

## 📊 Monitoring

Rate limiter menggunakan cache untuk menyimpan data percobaan login. Data akan tersimpan dengan key:

```
{email}|{ip_address}
```

Contoh: `user@example.com|192.168.1.1`
