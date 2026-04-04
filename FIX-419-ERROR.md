# Perbaikan Error 419 Page Expired pada Login

## Tanggal: 4 Maret 2026

## Masalah
Setelah input username dan password pada halaman login, muncul error 419 Page Expired.

## Penyebab
Error 419 umumnya disebabkan oleh:
1. CSRF token expired atau tidak valid
2. Session tidak tersimpan dengan benar
3. Konfigurasi session yang tidak tepat untuk HTTPS/reverse proxy
4. Missing konfigurasi untuk trusted proxies

## Perbaikan yang Dilakukan

### 1. Tambah Konfigurasi Session di .env
```
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

### 2. Buat TrustProxies Middleware
File: `app/Http/Middleware/TrustProxies.php`
- Middleware ini menangani header dari reverse proxy (nginx)
- Memastikan Laravel mengenali HTTPS dengan benar

### 3. Update Bootstrap Configuration
File: `bootstrap/app.php`
- Menambahkan `$middleware->trustProxies(at: '*')`
- Memastikan aplikasi mempercayai proxy headers

### 4. Clear All Caches
```bash
php artisan optimize:clear
php artisan config:cache
```

### 5. Hapus Session Lama
Menghapus semua file session lama di `storage/framework/sessions/`

## Testing
1. Buka halaman login: https://hris.aratechnology.id/login
2. Input username dan password
3. Login seharusnya berhasil tanpa error 419

## File Test Session (Optional)
Untuk testing session PHP: https://hris.aratechnology.id/test-session.php
File ini bisa dihapus setelah testing selesai.

## Catatan Penting
- CSRF token (@csrf) sudah ada di form login
- Session directory writable: OK
- APP_KEY sudah terkonfigurasi dengan benar
- APP_URL sudah sesuai: https://hris.aratechnology.id

## Jika Masih Error
1. Coba clear browser cache dan cookies
2. Coba buka di incognito/private window
3. Pastikan PHP-FPM sudah restart (memerlukan akses root):
   ```bash
   sudo systemctl restart php8.3-fpm
   ```
4. Check log Laravel: `storage/logs/laravel.log`
