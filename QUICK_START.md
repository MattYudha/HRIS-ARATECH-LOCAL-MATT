# 🚀 QUICK START GUIDE - HRIS Database

## ⚡ 3-Step Setup

### Step 1: Import Database (Choose ONE method)

**Method A: MySQL Command Line** (Recommended)
```bash
mysql -u your_username -p hrappsprod < hrapps_prod-fix.sql
```

**Method B: phpMyAdmin/CloudPanel**
1. Login to phpMyAdmin
2. Select database: `hrappsprod`
3. Click **Import** tab
4. Choose file: `hrapps_prod-fix.sql`
5. Click **Go**
6. Wait for completion (≈30 seconds)

### Step 2: Update Laravel .env
```env
DB_DATABASE=hrappsprod
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### Step 3: Clear Laravel Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

## ✅ Test Login

Navigate to your app and login with:
```
Email:    admin@aratechnology.id
Password: Password123!
```

**Success!** You should see the dashboard.

---

## 📋 What You Get

✅ **70+ Tables** - Complete HRIS schema  
✅ **5 Test Users** - Ready to login  
✅ **Sample Data** - Attendance, leave, payroll, KPI  
✅ **Laravel Compatible** - Standard authentication  
✅ **Multi-Module** - HR, Payroll, KPI, Inventory, Tasks, Letters

---

## 👥 Test Accounts

| Email | Password | Role | Access Level |
|-------|----------|------|--------------|
| admin@aratechnology.id | Password123! | Administrator | Full |
| manager.it@aratechnology.id | Password123! | Manager | Departments |
| manager.hr@aratechnology.id | Password123! | Manager | Departments |
| john.dev@aratechnology.id | Password123! | Employee | Self |
| jane.hr@aratechnology.id | Password123! | Employee | Self |

---

## 🔍 Quick Verification

### Check tables imported
```sql
SELECT COUNT(*) as table_count 
FROM information_schema.tables 
WHERE table_schema = 'hrappsprod';
```
**Expected:** 70+

### Check users
```sql
SELECT id, name, email, active FROM users;
```
**Expected:** 5 rows

### Check dummy data
```sql
SELECT COUNT(*) FROM employees;  -- Expected: 5
SELECT COUNT(*) FROM departments;  -- Expected: 4
SELECT COUNT(*) FROM attendance WHERE work_date = CURDATE();  -- Expected: 3
```

---

## 🆘 Troubleshooting

### ❌ Login fails
```sql
-- Check user is active
SELECT id, email, active FROM users WHERE email = 'admin@aratechnology.id';
-- active should be 1
```

### ❌ Import error: "Table already exists"
```bash
# Drop and recreate database
mysql -u username -p -e "DROP DATABASE IF EXISTS hrappsprod; CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
# Then import again
mysql -u username -p hrappsprod < hrapps_prod-fix.sql
```

### ❌ "Access denied"
Check your `.env` file:
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrappsprod
DB_USERNAME=your_username  # ← Correct username?
DB_PASSWORD=your_password  # ← Correct password?
```

---

## 📖 Full Documentation

See `DATABASE_SUMMARY.md` for:
- Complete table list
- Relationships diagram
- Security notes
- Best practices
- Advanced troubleshooting

---

## 🎯 Next Steps

1. ✅ Import complete
2. ✅ Login successful
3. 🔄 **Now:** Explore features
   - View employees
   - Check attendance
   - Review KPI dashboard
   - Generate payslips
4. 🔧 **Then:** Customize
   - Update company info
   - Add real employees
   - Configure departments
   - Set pay grades

---

**Need Help?** Check `DATABASE_SUMMARY.md` for comprehensive documentation.

**Version:** 1.0 | **Date:** 2025-12-27
