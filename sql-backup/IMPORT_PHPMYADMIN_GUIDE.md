# 📦 Import Database via phpMyAdmin

File **hrappsprod_PHPMYADMIN.sql** telah dibuat khusus untuk import via phpMyAdmin.

---

## ⚠️ Perbedaan dengan File Sebelumnya

| File | Status | Masalah |
|------|--------|---------|
| `hrappsprod_COMPLETE_FINAL.sql` | ❌ Gagal | Lines terlalu panjang (11,743 karakter) |
| `hrappsprod_PHPMYADMIN.sql` | ✅ Aman | Lines pendek, compatible dengan phpMyAdmin |

**Masalah yang diperbaiki:**
- Long lines dipecah menjadi INSERT statements terpisah
- No foreign keys yang kompleks (simplified)
- SET commands di awal untuk disable checks
- Total 24 core tables dengan dummy data

---

## 📋 Step-by-Step Import

### 1. Akses phpMyAdmin
URL: https://157.66.35.79:8443/phpmyadmin/index.php

### 2. Login Database
- Username: [your database username]
- Password: [your database password]

### 3. Pilih Database
- Klik database `hrappsprod` di sidebar kiri
- Atau buat database baru jika belum ada:
  ```sql
  CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  ```

### 4. Import File
1. Klik tab **"Import"** di top menu
2. Click **"Choose File"** button
3. Pilih file: **hrappsprod_PHPMYADMIN.sql**
4. Settings:
   - Format: **SQL**
   - Character set: **utf8mb4**
   - Format-specific options: [biarkan default]
5. Scroll ke bawah, klik **"Import"**

### 5. Tunggu Proses
- Import akan memakan waktu 10-30 detik
- Jangan refresh atau close browser

### 6. Verifikasi
Setelah import berhasil, jalankan query ini di SQL tab:

```sql
-- Check total tables
SELECT COUNT(*) as total_tables 
FROM information_schema.tables 
WHERE table_schema = 'hrappsprod';
-- Expected: 24 tables

-- Check users
SELECT id, name, email FROM users;
-- Expected: 5 users

-- Check employees
SELECT id, code, fullname, status FROM employees;
-- Expected: 5 employees

-- Check employee families
SELECT id, employee_id, fullname, relationship FROM employee_families;
-- Expected: 6 records

-- Check presences today
SELECT e.fullname, p.check_in, p.check_out, p.work_type 
FROM presences p
JOIN employees e ON p.employee_id = e.id
WHERE p.date = '2025-12-27';
-- Expected: 5 records

-- Check leave balances
SELECT e.fullname, lb.year, lb.leave_type, lb.remaining_days
FROM leave_balances lb
JOIN employees e ON lb.employee_id = e.id;
-- Expected: 7 records
```

---

## ✅ Tables Created (24 Total)

### Foundation (4)
- foundations
- departments
- job_positions
- pay_grades

### Users & Auth (3)
- users
- password_reset_tokens
- sessions

### Employee Management (4)
- education_levels
- employees
- employee_positions
- employee_families ✨

### Employee Details (2)
- employee_contacts ✨
- employee_documents ✨

### Attendance (2)
- attendance
- presences ✨

### Leave Management (1)
- leave_balances ✨

### Laravel System (8)
- migrations
- failed_jobs
- cache
- cache_locks
- jobs
- job_batches

---

## 🔑 Test Login Credentials

After successful import, test login dengan:

**Email:** admin@aratechnology.id  
**Password:** Password123!

**Other test accounts:**
- budi@aratechnology.id / Password123!
- siti@aratechnology.id / Password123!
- andi@aratechnology.id / Password123!
- dewi@aratechnology.id / Password123!

---

## 🚫 Troubleshooting

### Error: "MySQL server has gone away"
**Solution:** File terlalu besar untuk PHP settings
```
1. Buka php.ini
2. Edit settings:
   upload_max_filesize = 128M
   post_max_size = 128M
   max_execution_time = 300
3. Restart web server
```

### Error: "Unknown database"
**Solution:** Create database dulu
```sql
CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Error: "Duplicate entry"
**Solution:** Drop database dan import ulang
```sql
DROP DATABASE hrappsprod;
CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hrappsprod;
-- Then import again
```

### Error: "Table already exists"
**Solution:** File sudah include DROP TABLE statements, tapi bisa manual:
```sql
-- Drop all tables
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS users, employees, employee_families, 
  employee_contacts, employee_documents, presences, 
  leave_balances, attendance, departments, job_positions;
SET FOREIGN_KEY_CHECKS = 1;
```

---

## 📊 Dummy Data Summary

### 5 Users/Employees
1. **Admin User** (EMP001) - IT Manager - Married with 2 children
2. **Budi Santoso** (EMP002) - IT Manager - Married
3. **Siti Aminah** (EMP003) - HR Manager - Married with 1 child
4. **Andi Wijaya** (EMP004) - Staff IT - Single
5. **Dewi Lestari** (EMP005) - Staff HR - Single

### 6 Family Members
- Admin: Wife (Sari Dewi) + 2 children (Ahmad, Fitri)
- Budi: Wife (Dewi Kusuma)
- Siti: Husband (Rudi Hartono) + 1 child (Nisa)

### 5 Emergency Contacts
- Each employee has 1 primary emergency contact

### 4 Employee Documents
- Employment contracts & professional certificates

### 5 Presences (2025-12-27)
- Today's attendance with GPS coordinates
- Mix of WFO and WFH

### 7 Leave Balances (2025)
- Annual leave and sick leave for all employees

---

## 🎯 Next Steps After Import

1. ✅ Verify import successful
2. ✅ Test login to application
3. ⏳ Update Laravel models (see UPDATE_MODELS_GUIDE.md)
4. ⏳ Configure .env database connection
5. ⏳ Run: `php artisan config:cache`
6. ⏳ Test application features

---

**File:** hrappsprod_PHPMYADMIN.sql (safe for phpMyAdmin)  
**Size:** ~50 KB (smaller than original)  
**Lines:** Moderate length (max ~500 characters per line)  
**Compatible:** ✅ phpMyAdmin, MySQL 8.0+, MariaDB 10.5+
