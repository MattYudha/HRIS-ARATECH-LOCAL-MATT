# 🐳 Panduan Menjalankan HRIS Aratech dengan Docker

## Syarat

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) sudah terinstall dan berjalan
- Git (untuk clone project)

---

## ⚡ Cara Cepat (PC Baru)

```bash
# 1. Clone project
git clone <url-repository> HRIS_aratech
cd HRIS_aratech

# 2. Build & jalankan semua layanan
docker compose up -d --build

# 3. Tunggu ~60 detik sampai database selesai import
# Cek status:
docker compose logs -f app

# 4. Buka browser
# http://localhost:8000
```

**Login:**
- Email: `admin@aratech.id`
- Password: `Admin@1234`

---

## 📦 Struktur Layanan

| Container | Deskripsi | Port |
|-----------|------------|------|
| `hris_app` | PHP 8.2-FPM + Laravel | Internal |
| `hris_nginx` | Nginx Web Server | **8000** |
| `hris_db` | MySQL 8.0 | 3307 (local only) |

---

## 🔧 Perintah Berguna

```bash
# Lihat log semua container
docker compose logs -f

# Jalankan artisan command
docker compose exec app php artisan <command>

# Buat akun admin baru
docker compose exec app php create_admin.php

# Masuk ke shell container
docker compose exec app sh

# Koneksi ke MySQL langsung
docker compose exec db mysql -u hris_user -phris_secret hrappsprod

# Stop semua container (data tetap tersimpan)
docker compose down

# Stop dan HAPUS semua data (reset total)
docker compose down -v
```

---

## 🔄 Update Kode

Jika ada perubahan kode, rebuild image:

```bash
docker compose up -d --build
```

---

## ❓ Troubleshooting

**Container app keluar terus (exit)?**
```bash
docker compose logs app
```
Biasanya masalah DB belum siap. Coba:
```bash
docker compose down && docker compose up -d --build
```

**Port 8000 sudah dipakai?**
Edit `docker-compose.yml` baris ports webserver:
```yaml
ports:
  - "8080:80"   # Ganti 8000 dengan port lain
```

**Data MySQL ingin direset?**
```bash
docker compose down -v   # Hapus volume mysql_data
docker compose up -d --build
```
