# 📚 HRIS Database Documentation

## 📁 Files in This Directory

### 🔴 **MAIN IMPORT FILE** (Use This!)
- **`hrapps_prod-fix.sql`** (97 KB)
  - ✅ Laravel-compatible authentication
  - ✅ Complete schema with 70+ tables
  - ✅ Dummy data for all modules
  - ✅ Ready-to-use login credentials
  - **Database:** `hrappsprod`

### 📘 Documentation Files
1. **`QUICK_START.md`** ← **START HERE!**
   - 3-step import guide
   - Login credentials
   - Quick verification commands
   - Basic troubleshooting

2. **`DATABASE_SUMMARY.md`**
   - Complete technical documentation
   - All 70+ tables listed and described
   - Data relationships
   - Security notes
   - Advanced troubleshooting
   - Best practices

### 🗃️ Reference/Backup Files (Optional)
- `hrapps_combined_schema.sql` - DBML schema (not Laravel-compatible)
- `hrapps_laravel_compatible.sql` - Partial implementation
- `hrapps_prod-old.sql` - Legacy backup
- Various `.bak` files - Automatic backups

---

## 🚀 Quick Start (30 seconds)

### 1. Import Database
```bash
mysql -u your_username -p hrappsprod < hrapps_prod-fix.sql
```

### 2. Update .env
```env
DB_DATABASE=hrappsprod
```

### 3. Clear Cache
```bash
php artisan config:clear && php artisan cache:clear
```

### 4. Login
```
Email:    admin@aratechnology.id
Password: Password123!
```

**Done!** ✅

---

## 📊 Database Overview

| Module | Tables | Features |
|--------|--------|----------|
| **Authentication** | 9 | Laravel standard, multi-role |
| **Employee Management** | 5 | Positions, education, families |
| **Attendance** | 1 | GPS tracking, check-in/out |
| **Leave Management** | 4 | Requests, approvals, categories |
| **Payroll** | 6 | Periods, components, payslips |
| **KPI System** | 15 | Templates, tracking, reviews |
| **Inventory** | 3 | Categories, items, usage logs |
| **Tasks** | 1 | Task assignment & tracking |
| **Letters** | 4 | Templates, generation, archive |
| **Digital Signatures** | 2 | Signing & verification |
| **Foundation** | 4 | Multi-org, departments, positions |

**Total:** 70+ tables with foreign key relationships

---

## 👥 Test Users

| Email | Role | Features Access |
|-------|------|----------------|
| admin@aratechnology.id | Administrator | All modules |
| manager.it@aratechnology.id | IT Manager | Dept management |
| manager.hr@aratechnology.id | HR Manager | Dept management |
| john.dev@aratechnology.id | Developer | Self-service |
| jane.hr@aratechnology.id | HR Staff | Self-service |

**All passwords:** `Password123!`

---

## 📖 Documentation Guide

### For Quick Setup
👉 Read **`QUICK_START.md`** (5 minutes)

### For Complete Understanding
👉 Read **`DATABASE_SUMMARY.md`** (20 minutes)
- Table descriptions
- Relationships
- Data flows
- Security considerations
- Troubleshooting guide

---

## ✅ Import Verification

After import, run these checks:

```sql
-- Check table count
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'hrappsprod';
-- Expected: 70+

-- Check users
SELECT id, name, email, active FROM users;
-- Expected: 5 users

-- Check relationships
SELECT 
  e.fullname, 
  u.email, 
  d.department_name,
  jp.title
FROM employees e
JOIN users u ON e.user_id = u.id
JOIN employee_positions ep ON e.employee_id = ep.employee_id
JOIN departments d ON ep.department_id = d.department_id
JOIN job_positions jp ON ep.position_id = jp.position_id;
-- Expected: 5 rows with complete data
```

---

## 🔐 Important Notes

### Security
- ⚠️ **Change default passwords** after import
- ✅ All passwords use bcrypt (cost 12)
- ✅ Email verification supported
- ✅ Soft deletes enabled on key tables

### Laravel Compatibility
- ✅ Standard `users` table structure
- ✅ `users.id` as primary key (bigint unsigned)
- ✅ Timestamps (`created_at`, `updated_at`)
- ✅ Remember token for persistent login
- ✅ Compatible with Laravel 10+

### Database Requirements
- MySQL 5.7+ or MySQL 8.0+
- MariaDB 10.2+
- Character set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`
- Storage engine: `InnoDB`

---

## 🆘 Getting Help

### Common Issues
1. **Login fails** → Check `QUICK_START.md` → Troubleshooting section
2. **Import errors** → Check `DATABASE_SUMMARY.md` → Common Issues section
3. **Missing tables** → Verify import completed successfully
4. **Foreign key errors** → Ensure MySQL version compatibility

### Documentation Structure
```
README_DATABASE.md (You are here - Index)
├── QUICK_START.md (Import & basic setup)
└── DATABASE_SUMMARY.md (Complete technical docs)
```

---

## 🎯 Next Steps After Import

1. ✅ **Import completed**
2. ✅ **Login verified**
3. 🔄 **Test features:**
   - View employee list
   - Check today's attendance
   - Review leave requests
   - Generate test payslip
   - Check KPI dashboard
4. 🔧 **Customize:**
   - Update company information
   - Add real employees
   - Configure departments
   - Set salary grades

---

## 📞 Support Resources

- **Quick Start:** `QUICK_START.md`
- **Full Documentation:** `DATABASE_SUMMARY.md`
- **Laravel Docs:** https://laravel.com/docs/authentication
- **MySQL Docs:** https://dev.mysql.com/doc/

---

## 📝 File Manifest

### Essential Files
- ✅ `hrapps_prod-fix.sql` - **IMPORT THIS**
- ✅ `QUICK_START.md` - **READ THIS FIRST**
- ✅ `DATABASE_SUMMARY.md` - **COMPLETE REFERENCE**
- ✅ `README_DATABASE.md` - **THIS FILE**

### Optional Files
- `hrapps_combined_schema.sql` - Reference only
- `hrapps_laravel_compatible.sql` - Reference only
- `hrapps_prod-old.sql` - Backup
- `*.bak*` - Automatic backups

---

**Version:** 1.0  
**Last Updated:** 2025-12-27  
**Database:** hrappsprod  
**Laravel:** 10+ Compatible  
**MySQL:** 5.7+/8.0+ Required

---

**🚀 Ready to start? Open `QUICK_START.md` now!**
