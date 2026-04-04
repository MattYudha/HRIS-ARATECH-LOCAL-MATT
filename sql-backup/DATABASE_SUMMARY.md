# DATABASE SCHEMA SUMMARY - HRIS Application
**Generated:** 2025-12-27  
**Database:** `hrappsprod`  
**Compatibility:** Laravel 10+ Authentication

---

## 🔑 LOGIN CREDENTIALS
```
Email:    admin@aratechnology.id
Password: Password123!
```

Alternative logins:
- manager.it@aratechnology.id / Password123!
- manager.hr@aratechnology.id / Password123!
- john.dev@aratechnology.id / Password123!
- jane.hr@aratechnology.id / Password123!

---

## 📊 DATABASE STRUCTURE

### **CORE MODULES** (70+ Tables)

#### 1. **FOUNDATION & ORGANIZATION** (3 tables)
- `foundations` - Multi-org support (2 dummy foundations)
- `departments` - 4 departments (IT, HR, Finance, Operations)
- `job_positions` - 5 positions (CEO, Managers, Staff)

#### 2. **USER & AUTHENTICATION** (5 tables) ✅ Laravel Compatible
- `users` - **PRIMARY KEY: `id` (bigint auto_increment)** ← Laravel standard
  - Links to `employees` via `employee_id` column
  - Contains: email, password, name, phone, profile_picture, active status
- `user_types` - 3 types (Administrator, Manager, Employee)
- `roles` - 4 roles (Power User, Manager, Employee, HR Admin)
- `list_menu_features` - 9 menu items
- `user_type_roles` - Permission matrix

**Key Changes from DBML:**
- ✅ Changed `users.user_id varchar(16)` → `users.id bigint unsigned`
- ✅ Added `users.employee_id` back-reference
- ✅ Updated all foreign keys referencing users to point to `users.id`

#### 3. **EMPLOYEE MANAGEMENT** (5 tables)
- `employees` - 5 dummy employees (linked to users via `user_id` → `users.id`)
- `education_levels` - 4 education levels
- `employee_positions` - Current & historical positions
- `employee_families` - Family members (2 records for Admin)
- `pay_grade` - 4 salary grades

#### 4. **DOCUMENT MANAGEMENT** (3 tables)
- `identity_types` - KTP, Passport, Driver License, NPWP
- `document_identity` - Identity documents
- `bank_account` - Bank account info (3 accounts)

#### 5. **ATTENDANCE & TIME** (1 table)
- `attendance` - Check in/out with GPS (3 records for today)

#### 6. **LEAVE & APPROVALS** (4 tables)
- `approval_types` - Leave, Overtime, Reimbursement, Permission
- `category_approvals` - 6 categories
- `approval_requests` - 2 sample requests (1 approved, 1 pending)
- `approved` - Approval history

#### 7. **PAYROLL SYSTEM** (6 tables)
- `payroll_period` - Monthly periods
- `pay_component` - 6 components (Basic, Allowances, Deductions)
- `pay_grade_component` - Default amounts per grade
- `employee_pay_component` - Employee-specific overrides
- `payslip` - Generated payslips (2 samples)
- `payslip_line` - Payslip line items

#### 8. **KPI MANAGEMENT SYSTEM** (14 tables)
Comprehensive KPI tracking with:
- `kpi_period` - Quarterly/annual periods
- `kpi_scale` - Rating scales (1-5, 1-100)
- `kpi_scale_level` - Scale definitions
- `kpi_category` - Productivity, Quality, Teamwork
- `kpi_indicator` - Measurable indicators
- `kpi_template` - Position-based templates
- `kpi_template_item` - Template indicators
- `employee_kpi` - Employee KPI assignments
- `employee_kpi_item` - Individual KPI items
- `kpi_checkin` - Progress check-ins
- `kpi_evidence` - Supporting documents
- `kpi_review` - Manager reviews
- `kpi_score` - Calculated scores
- `kpi_approval` - Approval workflow
- `kpi_evaluations` - Final evaluations
- `performance_reviews` - Comprehensive reviews

#### 9. **INVENTORY MANAGEMENT** (3 tables)
- `inventory_categories` - Electronics, Furniture, Stationery
- `inventories` - 3 items (laptops, monitors, desks)
- `inventory_usage_logs` - Assignment history

#### 10. **INCIDENT MANAGEMENT** (1 table)
- `incidents` - Safety/security incidents (1 sample)

#### 11. **TASK MANAGEMENT** (1 table)
- `tasks` - Employee tasks (3 samples)

#### 12. **LETTER/DOCUMENT SYSTEM** (4 tables)
- `letter_templates` - 2 templates (Employment, Reference)
- `letter_configurations` - Company letterhead config
- `letters` - Generated letters (1 approved)
- `letter_archives` - Monthly summaries

#### 13. **DIGITAL SIGNATURE** (2 tables)
- `signatures` - Digital signatures with hash verification
- `signature_verifications` - Verification audit trail

#### 14. **LARAVEL SYSTEM TABLES** (8 tables)
- `cache` - Application cache
- `cache_locks` - Cache locks
- `failed_jobs` - Failed queue jobs
- `jobs` - Queue jobs
- `job_batches` - Batch jobs
- `migrations` - Migration history
- `password_reset_tokens` - Password reset
- `sessions` - User sessions

---

## 🔗 KEY RELATIONSHIPS

### User-Employee Link (Bidirectional)
```
users.id → employees.user_id
users.employee_id → employees.employee_id
```

### Foreign Key Chain Examples
```
users → employees → employee_positions → departments
                                      → job_positions
                                      → pay_grade

employees → attendance
         → approval_requests → approved
         → payslip → payslip_line
         → employee_kpi → employee_kpi_item → kpi_checkin
         → tasks
         → incidents
```

---

## 📝 DUMMY DATA SUMMARY

### Users & Employees
| ID | Name | Email | Type | Department | Position | Status |
|----|------|-------|------|------------|----------|--------|
| 1 | Admin User | admin@aratechnology.id | Administrator | IT | CEO | Active |
| 2 | IT Manager | manager.it@aratechnology.id | Manager | IT | IT Manager | Active |
| 3 | HR Manager | manager.hr@aratechnology.id | Manager | HR | HR Manager | Active |
| 4 | John Developer | john.dev@aratechnology.id | Employee | IT | Software Developer | Active |
| 5 | Jane HR | jane.hr@aratechnology.id | Employee | HR | HR Staff | Active |

### Attendance Today (2025-12-27)
- Admin: 08:00-17:00 (Office)
- IT Manager: 08:15-present (Office)
- John Developer: 08:00-17:00 (WFH)

### Leave Requests
- John Developer: Approved vacation (Dec 28-30)
- Jane HR: Pending sick leave (today)

### Payroll
- November 2025 payslips generated for Admin & IT Manager
- December 2025 period: Open

### KPI
- John Developer has active Q4-2025 KPI with 2 check-ins

---

## 🔧 TECHNICAL SPECIFICATIONS

### Encoding & Collation
- **Charset:** `utf8mb4`
- **Collation:** `utf8mb4_unicode_ci`
- **Engine:** `InnoDB` (all tables)

### Laravel Compatibility
- ✅ Standard `users` table structure
- ✅ `id` as primary key (bigint unsigned auto_increment)
- ✅ `email` unique constraint
- ✅ `password` field for bcrypt hashes
- ✅ `remember_token` for "Remember Me"
- ✅ `email_verified_at` timestamp
- ✅ `created_at`, `updated_at` timestamps
- ✅ Soft deletes support (`deleted_at` on applicable tables)

### Security Features
- Bcrypt password hashing (cost 12)
- Email verification support
- Remember token for persistent auth
- Active/inactive user status
- Soft delete for audit trail

---

## 📁 FILES GENERATED

1. **`hrapps_laravel_compatible.sql`** - Main import file (Laravel-compatible)
2. **`hrapps_combined_schema.sql`** - Original DBML structure (reference only)
3. **`DATABASE_SUMMARY.md`** - This documentation

### Backups Created
- `hrapps_combined_schema.sql.bak_original` - Original DBML schema backup

---

## 🚀 IMPORT INSTRUCTIONS

### Method 1: MySQL Command Line
```bash
mysql -u your_username -p hrappsprod < hrapps_laravel_compatible.sql
```

### Method 2: CloudPanel/phpMyAdmin
1. Drop all existing tables in `hrappsprod` database
2. Select `hrappsprod` database
3. Go to **Import** tab
4. Choose file: `hrapps_laravel_compatible.sql`
5. Click **Go**

### Method 3: Laravel Artisan (if migrations exist)
```bash
php artisan migrate:fresh --seed
```

---

## ✅ POST-IMPORT VERIFICATION

### 1. Check Table Count
```sql
SELECT COUNT(*) FROM information_schema.tables 
WHERE table_schema = 'hrappsprod';
-- Expected: 70+ tables
```

### 2. Verify Users
```sql
SELECT id, name, email, active FROM users;
-- Should show 5 users
```

### 3. Test Login
- Navigate to app login page
- Use: `admin@aratechnology.id` / `Password123!`
- Should successfully authenticate

### 4. Check Foreign Keys
```sql
SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE 
WHERE table_schema = 'hrappsprod' 
AND referenced_table_name IS NOT NULL;
-- Expected: 50+ foreign keys
```

---

## 🎯 NEXT STEPS AFTER IMPORT

1. **Verify Login Works**
   - Test with admin@aratechnology.id
   - Check dashboard loads correctly

2. **Update .env**
   ```
   DB_DATABASE=hrappsprod
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. **Clear Laravel Cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

4. **Test Key Features**
   - Employee list
   - Attendance check-in
   - Leave request
   - Payslip view
   - KPI dashboard

5. **Customize Data**
   - Update company info in `letter_configurations`
   - Modify foundation details in `foundations`
   - Adjust departments as needed
   - Add real employees

---

## 🆘 TROUBLESHOOTING

### Login Not Working
- Verify `users` table has records
- Check password hash is bcrypt
- Confirm `active = 1`
- Check Laravel `config/auth.php` uses `users` table

### Foreign Key Errors
- Ensure tables created in correct order
- Check foreign key constraints enabled
- Verify referenced records exist

### Import Fails
- Check MySQL version (5.7+ or 8.0+ required)
- Ensure `hrappsprod` database exists
- Try importing in parts (split by section)

---

## 📞 SUPPORT

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check MySQL errors during import
3. Verify .env database credentials
4. Test database connection: `php artisan db:show`

---

**Schema Version:** 1.0  
**Last Updated:** 2025-12-27  
**Compatible With:** Laravel 10+, MySQL 5.7+/8.0+

---

## 📁 RECOMMENDED IMPORT FILE

**Use:** `hrapps_prod-fix.sql` (97 KB) ✅ **RECOMMENDED**

This file is:
- ✅ **Laravel authentication compatible**
- ✅ Contains complete schema with all 70+ tables
- ✅ Includes comprehensive dummy data
- ✅ Has working login credentials pre-configured
- ✅ Tested and verified for Laravel integration

**Alternative files (for reference only):**
- `hrapps_combined_schema.sql` - DBML schema (not Laravel-compatible)
- `hrapps_laravel_compatible.sql` - Partial implementation (incomplete)
- `hrapps_prod-old.sql` - Legacy backup

---

## 🗂️ COMPLETE TABLE LIST (by Module)

### Foundation & Organization (4 tables)
1. `foundations`
2. `departments`  
3. `job_positions`
4. `pay_grade`

### Users & Authentication (9 tables)
5. `users` ✅ **Laravel standard**
6. `user_types`
7. `roles`
8. `list_menu_features`
9. `user_type_roles`
10. `password_reset_tokens`
11. `sessions`
12. `migrations`
13. `failed_jobs`

### Employee Management (5 tables)
14. `employees`
15. `education_levels`
16. `employee_positions`
17. `employee_families`
18. `employee_pay_component`

### Documents & Identity (3 tables)
19. `identity_types`
20. `document_identity`
21. `bank_account`

### Attendance (1 table)
22. `attendance`

### Leave & Approvals (4 tables)
23. `approval_types`
24. `category_approvals`
25. `approval_requests`
26. `approved`

### Payroll (5 tables)
27. `payroll_period`
28. `pay_component`
29. `pay_grade_component`
30. `payslip`
31. `payslip_line`

### KPI System (15 tables)
32. `kpi_period`
33. `kpi_scale`
34. `kpi_scale_level`
35. `kpi_category`
36. `kpi_indicator`
37. `kpi_template`
38. `kpi_template_item`
39. `employee_kpi`
40. `employee_kpi_item`
41. `kpi_checkin`
42. `kpi_evidence`
43. `kpi_review`
44. `kpi_score`
45. `kpi_approval`
46. `kpi_evaluations`
47. `performance_reviews`

### Inventory (3 tables)
48. `inventory_categories`
49. `inventories`
50. `inventory_usage_logs`

### Incidents (1 table)
51. `incidents`

### Tasks (1 table)
52. `tasks`

### Letters & Documents (4 tables)
53. `letter_templates`
54. `letter_configurations`
55. `letters`
56. `letter_archives`

### Digital Signatures (2 tables)
57. `signatures`
58. `signature_verifications`

### Laravel System (6 tables)
59. `cache`
60. `cache_locks`
61. `jobs`
62. `job_batches`
63. `presences` (additional attendance tracking)
64. Other Laravel framework tables

**Total: 70+ tables**

---

## 🔐 SECURITY NOTES

### Password Storage
- All passwords use **bcrypt** with cost factor 12
- Format: `$2y$12$...`
- Never store plain-text passwords
- Test password: `Password123!`

### User Status
- `active = 1` → User can login
- `active = 0` → User disabled (login blocked)

### Email Verification
- `email_verified_at IS NOT NULL` → Verified
- `email_verified_at IS NULL` → Unverified (may block features)

### Soft Deletes
Tables with soft delete support (have `deleted_at` column):
- `employees`
- `departments`
- `attendance`
- `approval_requests`
- `payslip`
- `tasks`

**Soft delete behavior:**
- Record remains in database
- `deleted_at` timestamp marks deletion
- Laravel queries automatically exclude soft-deleted records
- Can be restored using `restore()` method

---

## 🎨 MENU STRUCTURE

### Administrator (Full Access)
1. 📊 Dashboard
2. 👥 Employees
3. 📅 Attendance  
4. 🏖️ Leave
5. 💰 Payroll
6. 📈 KPI
7. 📦 Inventory
8. 📄 Letters
9. 📊 Reports

### Manager (Limited)
1. 📊 Dashboard
2. 👥 Employees
3. 📅 Attendance
4. 🏖️ Leave
5. 📈 KPI

### Employee (Basic)
1. 📊 Dashboard
2. 📅 Attendance
3. 🏖️ Leave

---

## 🔄 DATA FLOW EXAMPLES

### Attendance Flow
```
User Login → Dashboard → Check In
              ↓
         attendance table
              ↓
    (lat, long, timestamp recorded)
```

### Leave Request Flow
```
Employee creates request
       ↓
approval_requests (status: pending)
       ↓
Manager reviews
       ↓
approved table (approved_by)
       ↓
approval_requests (status: approved/rejected)
```

### Payroll Generation Flow
```
payroll_period created
       ↓
Calculate: pay_grade_component + employee_pay_component
       ↓
Generate payslip
       ↓
Create payslip_line items
       ↓
Payslip ready (status: draft/paid)
```

### KPI Tracking Flow
```
kpi_template assigned to employee
       ↓
employee_kpi created
       ↓
employee_kpi_item (from template)
       ↓
kpi_checkin (progress updates)
       ↓
kpi_evidence (supporting docs)
       ↓
kpi_review (manager feedback)
       ↓
kpi_score calculated
       ↓
kpi_approval (final approval)
```

---

## 📊 DATABASE STATISTICS

### Data Volume (Dummy)
- 5 Users
- 5 Employees
- 4 Departments
- 5 Job Positions
- 3 Attendance records (today)
- 2 Leave requests
- 2 Payslips
- 1 Active KPI
- 3 Inventory items
- 3 Tasks
- 1 Letter

### Foreign Keys
- ~60+ foreign key constraints
- Ensures referential integrity
- Cascade deletes where appropriate

### Indexes
- Primary keys on all tables
- Unique indexes on email fields
- Foreign key indexes for performance
- Additional indexes on frequently queried columns

---

## 💡 TIPS & BEST PRACTICES

### 1. Always Backup Before Import
```bash
mysqldump -u username -p hrappsprod > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Test in Development First
- Never import directly to production
- Test all features after import
- Verify data integrity

### 3. Update Credentials
After successful import, immediately change:
- Admin password
- Database credentials in .env
- API keys if any

### 4. Monitor Performance
```sql
-- Check table sizes
SELECT 
  table_name, 
  ROUND((data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables 
WHERE table_schema = 'hrappsprod'
ORDER BY (data_length + index_length) DESC;
```

### 5. Regular Maintenance
```sql
-- Optimize tables monthly
OPTIMIZE TABLE users, employees, attendance, payslip;

-- Analyze tables for query optimization
ANALYZE TABLE users, employees, attendance;
```

---

## 🚨 COMMON ISSUES & SOLUTIONS

### Issue 1: "Table already exists"
**Solution:**
```sql
DROP DATABASE IF EXISTS hrappsprod;
CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hrappsprod;
-- Then import
```

### Issue 2: "Cannot add foreign key constraint"
**Solution:**
- Ensure parent table exists before child
- Check data types match exactly
- Verify referenced records exist

### Issue 3: Login shows "These credentials do not match"
**Solution:**
```sql
-- Check user exists and is active
SELECT id, email, active FROM users WHERE email = 'admin@aratechnology.id';

-- Verify password hash
SELECT password FROM users WHERE email = 'admin@aratechnology.id';

-- Reset password if needed (Laravel tinker)
-- Hash::make('Password123!')
```

### Issue 4: "Undefined index: employee_id"
**Solution:**
- Ensure `users.employee_id` column exists
- Run: `ALTER TABLE users ADD COLUMN employee_id INT DEFAULT NULL;`
- Update relationship: `UPDATE users u SET employee_id = (SELECT employee_id FROM employees e WHERE e.user_id = u.id);`

---

## 📚 REFERENCES

### Laravel Documentation
- [Authentication](https://laravel.com/docs/authentication)
- [Database Migrations](https://laravel.com/docs/migrations)
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)

### MySQL Documentation
- [Foreign Keys](https://dev.mysql.com/doc/refman/8.0/en/create-table-foreign-keys.html)
- [InnoDB Storage](https://dev.mysql.com/doc/refman/8.0/en/innodb-storage-engine.html)
- [UTF8MB4 Support](https://dev.mysql.com/doc/refman/8.0/en/charset-unicode-utf8mb4.html)

---

**Document Version:** 2.0  
**Last Updated:** 2025-12-27 10:15 WIB  
**Maintained By:** HRIS Development Team

