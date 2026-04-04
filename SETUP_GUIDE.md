# HRIS Aratech - Setup Guide

## Instalasi & Konfigurasi Awal

### 1. Prasyarat
- PHP 8.2+
- MySQL/MariaDB
- Composer
- Node.js (opsional, untuk build assets)

### 2. Setup Database

```bash
# Copy file .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=hrapps
# DB_USERNAME=root
# DB_PASSWORD=

# Jalankan migrasi
php artisan migrate
```

### 3. Seed Data (PENTING!)

Semua data dummy sudah dikonfigurasi permanent. Jalankan salah satu:

**Opsi A: Seed otomatis saat fresh install**
```bash
php artisan migrate:fresh --seed
```
Command ini akan:
- ✅ Reset database sepenuhnya
- ✅ Jalankan semua migrations
- ✅ Auto-seed semua data (roles, departments, employees, KPIs, templates, dll)

**Opsi B: Jika sudah ada database, hanya seed data**
```bash
php artisan db:seed
```

**Opsi C: Sync/Update data saja (tanpa reset)**
```bash
php artisan sync:dummy-data
```
Command ini aman karena menggunakan `firstOrCreate()` - tidak akan duplicate data

### 4. Data yang Tersimpan Permanent

Setelah seeding, sistem akan memiliki:

#### Akun User (untuk testing/demo):
1. **admin@example.com** (Password: password)
   - Role: HR
   - Akses lengkap ke semua menu

2. **manager@example.com** (Password: password)
   - Role: Manager
   - Akses: Team management, KPI, Reports

3. **employee3@example.com** (Password: password)
   - Role: Developer
   - Akses: Personal KPI, Letters, Tasks

4. **employee4@example.com** (Password: password)
   - Role: Power User
   - Akses: Extended features

5. **employee5@example.com** (Password: password)
   - Role: Power User
   - Akses: Extended features

#### Data Master:
- **4 Roles**: HR, Manager, Developer, Power User
- **5 Departments**: IT, HR, Marketing, Sales, Operations
- **10 KPIs**: Attendance, Productivity, Quality, Department, Behavior, Leave, Salary
- **5 Letter Templates**: Job Offer, Employment Contract, Recommendation, Certificate, Leave Permission
- **5 Inventory Categories** + **5 Items**
- **2 Sample Tasks** + **2 Sample Leave Requests**
- **50+ KPI Records** untuk 5 employees (periode 2025-12)

### 5. Menu Berdasarkan Role

**Admin/HR:**
- Dashboard
- Employees, Departments, Roles
- Tasks, Presences, Payrolls, Leave Requests
- Inventory (Categories, Items, Usage Logs)
- Letters (+ Templates, Config, Archives)
- Signature Logs
- KPI Dashboard, Executive Dashboard, Reports

**Manager:**
- Dashboard
- Tasks, Leave Requests
- Team KPI, Department KPI
- Monthly Reports
- Letters
- My KPI (Personal)

**Developer/Karyawan:**
- My KPI (Personal)
- Letters
- Tasks, Presences, Payrolls, Leave
- Inventory Usage, Letter Archives
- Signature Logs

### 6. Fitur Utama

✅ **Authentication & Authorization**
- Login multi-role
- Session management
- Profile dengan info employee

✅ **HR Management**
- Employee management
- Department & Role management
- Leave request tracking
- Task assignment & tracking

✅ **KPI System**
- KPI master data
- Employee KPI tracking (periode bulanan)
- Dashboard KPI personal, team, department
- Performance reports (executive & monthly)

✅ **Letter Module**
- Create & submit letters
- Letter templates (5 macam)
- Letter numbering system
- Approval workflow (draft → pending → approved → printed)
- PDF export

✅ **Digital Signature**
- Sign pad untuk setiap document
- Signature verification
- Signature logs

✅ **Inventory**
- Category management
- Item tracking
- Usage logging

### 7. Persistence Data

Semua data yang sudah di-seed disimpan **PERMANENT** di database MySQL:
- ✅ Data tidak hilang saat browser ditutup
- ✅ Data persisten saat server restart
- ✅ Database adalah single source of truth

**Jika ingin reset database:**
```bash
php artisan migrate:fresh --seed
```
Command ini akan menghapus dan membuat ulang database dengan data fresh.

### 8. Troubleshooting

**Jika letter submit tidak bekerja:**
- Pastikan Letter Configuration ada
- Jalankan: `php artisan sync:dummy-data`

**Jika menu tidak tampil:**
- Clear cache: `php artisan cache:clear`
- Logout dan login kembali

**Jika database error:**
- Pastikan kolom `phone_number` ada di employees table
- Pastikan `salary` field required tidak null

### 9. Development Tips

**Update seeder jika ada perubahan data:**
Edit file: `app/Console/Commands/SyncAllDummyData.php`

**Jalankan ulang seeding:**
```bash
php artisan sync:dummy-data
```

**Clear semua cache jika perlu:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## Kontak & Support

Untuk pertanyaan atau issues, silakan hubungi development team.

---
**Last Updated:** 04 December 2025
**Version:** 1.0 (Stable)
