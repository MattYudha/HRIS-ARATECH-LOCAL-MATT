# Testing Guide - Presence Alert Fix

## Skenario Testing

### ✅ Skenario 1: Happy Path - WFO dalam Radius
1. Buka `/presences/create`
2. **Expected**: Alert "Mohon izinkan akses lokasi" TIDAK terlihat (hidden)
3. Pilih WFO (default)
4. Klik tombol "Ambil Lokasi"
5. **Expected**: Alert muncul saat meminta izin GPS
6. Izinkan akses lokasi
7. **Expected**: 
   - Alert hilang
   - Checklist "Lokasi GPS" hijau (jika dalam radius 1000m)
   - Distance display menunjukkan jarak
   - Debug log: "Location OK: XXXm from office"
8. Pilih SSID WiFi kantor
9. Tunggu Face Liveness selesai
10. **Expected**: Tombol "Present" enabled

### ❌ Skenario 2: GPS Ditolak
1. Buka `/presences/create`
2. Pilih WFO
3. Klik "Ambil Lokasi"
4. Tolak izin GPS
5. **Expected**: 
   - Alert "Mohon izinkan akses lokasi" tetap terlihat
   - Alert popup browser: "Gagal lokasi: Izin ditolak"
   - Debug log: "Gagal lokasi: Izin ditolak."
   - Checklist "Lokasi GPS" tetap orange (pending)
   - Tombol "Present" disabled

### ⚠️ Skenario 3: GPS Berhasil tapi Luar Radius
1. Buka `/presences/create`
2. Pilih WFO
3. Klik "Ambil Lokasi"
4. Izinkan GPS (simulasi dari lokasi luar kantor > 1000m)
5. **Expected**:
   - Alert "Mohon izinkan akses lokasi" hilang (GPS granted)
   - Checklist "Lokasi GPS" MERAH (di luar radius)
   - Debug log: "Location: Outside radius (XXXXm > 1000m)"
   - Distance display menunjukkan jarak besar
   - Tombol "Present" disabled

### 🏠 Skenario 4: WFH/WFA (No GPS Required)
1. Buka `/presences/create`
2. Pilih WFH atau WFA
3. **Expected**:
   - Section GPS hidden
   - Alert tidak terlihat
   - Hanya butuh Fingerprint
   - Setelah Fingerprint ready → Tombol "Present" enabled

## Debug Log Reference

Monitor di bagian "Monitor Aktivitas (Debug)" untuk melihat:

```
[timestamp] Loading FingerprintJS...
[timestamp] Fingerprint Ready: abc123xyz
[timestamp] Device: Mobile
[timestamp] Starting GPS Search...
[timestamp] GPS Hooked: -6.36232, 106.64768 (Acc: 15m)
[timestamp] Distance to Office: 850 meter
[timestamp] Location OK: 850m from office
[timestamp] Button State: FP=true, Loc=true, WiFi=true, Face=true => ENABLED
```

## Troubleshooting

### Alert tidak hilang setelah GPS granted
- Cek apakah ada error di console browser
- Pastikan `checkReadyState()` dipanggil
- Cek nilai `isLocationOK` di debug log

### Tombol tetap disabled meskipun semua hijau
- Lihat debug log: "Button State: FP=X, Loc=X, WiFi=X, Face=X"
- Pastikan SEMUA bernilai `true`
- Clear cache browser (Ctrl+Shift+R)

### GPS akurasi mencurigakan
- Jika accuracy <= 1m, sistem akan alert: "Akurasi GPS mencurigakan"
- Ini indikasi Fake GPS app

## File yang Dimodifikasi
- `resources/views/presences/create.blade.php`
- Line 122: Alert dengan class `d-none`
- Line 342-404: Fungsi `checkReadyState()`
- Line 447-494: Fungsi `getGPSLocation()`
