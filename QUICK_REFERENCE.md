# HRIS Aratech - Quick Reference

## 🚀 Startup Commands

### First Time Setup (Fresh Install)
```bash
# 1. Setup environment
copy .env.example .env
php artisan key:generate

# 2. Setup database (with auto-seed)
php artisan migrate:fresh --seed
```

### Subsequent Startups
```bash
# Just start the server - data sudah permanent di database!
php artisan serve
# atau jika pakai XAMPP: buka http://localhost:8000
```

## 📊 Data Sync Commands

```bash
# Sync/update semua data dummy (aman, tidak duplicate)
php artisan sync:dummy-data

# Fresh database + seed semua data
php artisan migrate:fresh --seed

# Hanya seed data ke database yang sudah ada
php artisan db:seed
```

## 🔧 Maintenance Commands

```bash
# Clear cache jika ada issue
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear semua cache sekaligus

# Migrate database
php artisan migrate

# Check database status
php artisan tinker
```

## 👤 Test Akun (Built-in)

| Email | Password | Role | Akses |
|-------|----------|------|-------|
| admin@example.com | password | HR | Menu lengkap |
| manager@example.com | password | Manager | Team, KPI, Reports |
| employee3@example.com | password | Developer | Personal KPI, Tasks, Letters |
| employee4@example.com | password | Power User | Extended access |
| employee5@example.com | password | Power User | Extended access |

## 📍 URL Penting

| Halaman | URL |
|--------|-----|
| Dashboard | http://localhost:8000/dashboard |
| Login | http://localhost:8000/login |
| Employees | http://localhost:8000/employees |
| Tasks | http://localhost:8000/tasks |
| Letters | http://localhost:8000/letters |
| KPI Dashboard | http://localhost:8000/kpi/dashboard |
| Team KPI | http://localhost:8000/kpi/team |
| Reports | http://localhost:8000/reports/executive |
| Profile | http://localhost:8000/profile |

## 🗂️ Data Structure

### Database Tables (20+)
- users, employees, roles, departments
- tasks, leave_requests, presences, payrolls
- letters, letter_templates, letter_configurations
- signatures, signature_logs
- kpis, employee_kpi_records
- inventories, inventory_categories, inventory_usage_logs
- dan lainnya...

### Seeder File
- `app/Console/Commands/SyncAllDummyData.php` - Master seeder dengan semua data

### Model Files
- `app/Models/*` - 20+ model dengan relationships

## ⚙️ Konfigurasi Penting (.env)

```env
APP_NAME="HRIS Aratech"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrapps
DB_USERNAME=root
DB_PASSWORD=
```

## 🐛 Troubleshooting

### Database Issues
```bash
# Reset database dan seed ulang
php artisan migrate:fresh --seed

# Atau hanya reset tanpa seed
php artisan migrate:refresh
```

### Cache Issues
```bash
# Clear semua cache
php artisan optimize:clear
```

### Permission Issues
```bash
# Jika ada permission error, pastikan folder writable
chmod -R 775 storage bootstrap/cache
```

### Port Sudah Digunakan
```bash
# Gunakan port lain
php artisan serve --port=8001
```

## 📋 Permanent Data Checklist

✅ Roles (4) - tidak perlu di-setup lagi
✅ Departments (5) - tidak perlu di-setup lagi  
✅ Employees (5) - tidak perlu di-setup lagi
✅ KPIs (10) - tidak perlu di-setup lagi
✅ Letter Templates (5) - tidak perlu di-setup lagi
✅ Letter Configuration - tidak perlu di-setup lagi
✅ Inventory Categories (5) - tidak perlu di-setup lagi
✅ Inventory Items (5) - tidak perlu di-setup lagi
✅ Tasks & Leave Requests - sample data siap testing
✅ KPI Records (50+) - data siap untuk dashboard

## 🔐 Security Notes

- Akun test menggunakan password sederhana (untuk development saja!)
- Jangan gunakan di production tanpa mengubah password
- Update role-based access sesuai kebutuhan
- Implementasikan proper authentication untuk live

## 📞 Support

Jika ada issues:
1. Baca SETUP_GUIDE.md untuk detail lengkap
2. Jalankan `php artisan sync:dummy-data` untuk reset data
3. Clear cache dengan `php artisan optimize:clear`
4. Restart server

---
**Semua data sudah PERMANENT dan tidak akan hilang saat server restart! 🎉**
