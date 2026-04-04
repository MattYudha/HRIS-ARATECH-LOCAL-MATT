# 🚀 HRIS Database Migration Strategy

**Document Version:** 3.0  
**Last Updated:** 2025-12-27  
**Database Target:** `hrappsprod`  
**Framework:** Laravel 10+

---

## 📋 Table of Contents

1. [Executive Summary](#executive-summary)
2. [Current State Analysis](#current-state-analysis)
3. [Target Architecture](#target-architecture)
4. [Migration Files](#migration-files)
5. [Step-by-Step Migration](#step-by-step-migration)
6. [Post-Migration Tasks](#post-migration-tasks)
7. [Rollback Strategy](#rollback-strategy)
8. [Testing & Verification](#testing--verification)

---

## 1. Executive Summary

### Objective
Migrate HRIS application database to a unified, Laravel-compatible schema that supports:
- ✅ Laravel authentication (standard `users` table)
- ✅ Multi-module HR operations (70+ tables)
- ✅ Complete dummy data for testing
- ✅ Foreign key relationships & referential integrity

### Timeline
- **Preparation:** 30 minutes (backup, review)
- **Migration:** 5-10 minutes (import SQL)
- **Verification:** 15 minutes (testing, validation)
- **Total:** ~1 hour

### Key Changes
1. **Users Table:** Converted from DBML `user_id varchar(16)` to Laravel `id bigint unsigned`
2. **Schema Integration:** Combined features from GOHR2 + HRIS2 + Laravel requirements
3. **Data Compatibility:** All foreign keys updated to match Laravel standards

---

## 2. Current State Analysis

### Source Files Available

#### A. Primary Import File (RECOMMENDED)
```
hrapps_laravel_compatible_COMPLETE.sql (97 KB)
├─ Complete Laravel-compatible schema
├─ 70+ tables with foreign keys
├─ Comprehensive dummy data
├─ Ready for production use
└─ Source: hrapps_prod-fix.sql (tested & verified)
```

#### B. Reference Files
```
combined_hris_prod.sql
├─ Original DBML schema definition
├─ Contains business logic documentation
└─ NOT Laravel-compatible (reference only)

hrapps_combined_schema.sql
├─ DBML to SQL conversion
├─ Schema-only (no data)
└─ NOT Laravel-compatible (reference only)

hrapps_prod-old.sql
├─ Legacy backup
└─ Contains old production data
```

### Schema Sources Merged

#### From GOHR2:
- User management & authentication
- Employee records & positions
- Attendance tracking
- Leave management
- Payroll system

#### From HRIS2 (Unique Features):
- Inventory management
- Incident tracking
- Task management
- Letter/document generation
- Digital signatures

#### Laravel Standard:
- Authentication tables
- Cache & sessions
- Job queues
- Password resets

---

## 3. Target Architecture

### Database: `hrappsprod`
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci
- **Engine:** InnoDB
- **MySQL Version:** 5.7+ or 8.0+

### Table Structure (70+ tables)

#### Core Modules

##### 1. Foundation & Organization (4 tables)
```sql
foundations              -- Multi-org support
departments             -- Hierarchical departments
job_positions           -- Position catalog
pay_grade              -- Salary grades
```

##### 2. User Authentication (9 tables) ✅ Laravel Standard
```sql
users                   -- PRIMARY KEY: id (bigint unsigned)
  ├─ id (auto_increment)
  ├─ email (unique)
  ├─ password (bcrypt)
  ├─ employee_id (links to employees)
  └─ Laravel standard fields

user_types             -- Administrator, Manager, Employee
roles                  -- Power User, Manager, Employee, HR Admin
list_menu_features     -- Menu structure
user_type_roles        -- Permission matrix
password_reset_tokens  -- Password recovery
sessions               -- Active sessions
migrations             -- Schema version
failed_jobs            -- Failed queue jobs
```

##### 3. Employee Management (5 tables)
```sql
employees              -- Core employee data
  ├─ user_id → users.id (bidirectional link)
  └─ employee_id (internal reference)

education_levels       -- Education qualifications
employee_positions     -- Position history
employee_families      -- Family members
employee_pay_component -- Salary overrides
```

##### 4. Attendance & Time (2 tables)
```sql
attendance            -- Check-in/out with GPS
presences             -- Enhanced presence tracking
```

##### 5. Leave & Approvals (4 tables)
```sql
approval_types        -- Leave, Overtime, Reimbursement
category_approvals    -- Approval categories
approval_requests     -- Request submissions
approved              -- Approval history
```

##### 6. Payroll System (6 tables)
```sql
payroll_period        -- Monthly periods
pay_component         -- Salary components
pay_grade_component   -- Grade defaults
employee_pay_component -- Employee overrides
payslip               -- Generated payslips
payslip_line          -- Payslip details
```

##### 7. KPI Management (15 tables)
```sql
kpi_period            -- Performance periods
kpi_scale             -- Rating scales
kpi_scale_level       -- Scale definitions
kpi_category          -- KPI categories
kpi_indicator         -- Measurable indicators
kpi_template          -- Position templates
kpi_template_item     -- Template items
employee_kpi          -- Employee assignments
employee_kpi_item     -- Individual items
kpi_checkin           -- Progress updates
kpi_evidence          -- Supporting documents
kpi_review            -- Manager reviews
kpi_score             -- Calculated scores
kpi_approval          -- Approval workflow
kpi_evaluations       -- Final evaluations
performance_reviews   -- Comprehensive reviews
```

##### 8. Inventory System (3 tables)
```sql
inventory_categories  -- Electronics, Furniture, etc
inventories           -- Item catalog
inventory_usage_logs  -- Assignment tracking
```

##### 9. Incident Management (1 table)
```sql
incidents             -- Safety/security incidents
```

##### 10. Task Management (1 table)
```sql
tasks                 -- Task assignment
```

##### 11. Letter/Document System (4 tables)
```sql
letter_templates      -- Letter templates
letter_configurations -- Company settings
letters               -- Generated letters
letter_archives       -- Monthly archives
```

##### 12. Digital Signatures (2 tables)
```sql
signatures            -- Digital signatures
signature_verifications -- Verification audit
```

##### 13. Document Management (3 tables)
```sql
identity_types        -- KTP, Passport, etc
document_identity     -- Identity documents
bank_account          -- Bank details
```

##### 14. Laravel System (6 tables)
```sql
cache                 -- Application cache
cache_locks           -- Lock mechanism
jobs                  -- Queue jobs
job_batches           -- Batch processing
```

### Key Relationships

#### Primary User Flow
```
users.id ←→ employees.user_id (bidirectional)
         ↓
    employees.employee_id
         ↓
    ├─ employee_positions → departments, job_positions
    ├─ attendance
    ├─ approval_requests → approved
    ├─ payslip → payslip_line
    ├─ employee_kpi → employee_kpi_item
    ├─ tasks
    └─ incidents
```

#### Authentication Chain
```
Login → users table (Laravel Auth)
     ↓
Check: email + password (bcrypt)
     ↓
Session created (sessions table)
     ↓
Load: employee_id → employees → employee_positions
     ↓
Determine: user_type_id → user_type_roles → menu_access
```

---

## 4. Migration Files

### File Hierarchy

```
Primary Migration Files:
├─ hrapps_laravel_compatible_COMPLETE.sql  ✅ USE THIS
│  ├─ Complete schema + data
│  ├─ Laravel-compatible
│  ├─ All 70+ tables
│  └─ Ready for import
│
├─ README_DATABASE.md
│  └─ Main documentation index
│
├─ QUICK_START.md
│  └─ 3-step import guide
│
└─ DATABASE_SUMMARY.md
   └─ Complete technical reference

Reference Files (DO NOT IMPORT):
├─ combined_hris_prod.sql (DBML schema - reference)
├─ hrapps_combined_schema.sql (incomplete)
└─ hrapps_prod-old.sql (legacy backup)
```

### File Details

#### hrapps_laravel_compatible_COMPLETE.sql
- **Size:** 97 KB
- **Tables:** 70+
- **Data:** Comprehensive dummy data
- **Features:**
  - ✅ Laravel `users` table (id as PK)
  - ✅ Bcrypt passwords
  - ✅ Foreign key constraints
  - ✅ Soft deletes
  - ✅ Timestamps
  - ✅ Test accounts configured
  - ✅ Sample data for all modules

---

## 5. Step-by-Step Migration

### Phase 1: Pre-Migration (15 minutes)

#### Step 1.1: Backup Current Database
```bash
# If database exists, backup first
mysqldump -u username -p hrappsprod > backup_hrappsprod_$(date +%Y%m%d_%H%M%S).sql

# Backup .env file
cp .env .env.backup_$(date +%Y%m%d_%H%M%S)
```

#### Step 1.2: Verify Requirements
```bash
# Check MySQL version
mysql --version
# Required: MySQL 5.7+ or 8.0+

# Check database connection
mysql -u username -p -e "SELECT VERSION();"

# Check Laravel version
php artisan --version
# Required: Laravel 10+
```

#### Step 1.3: Review Migration Plan
- [ ] Read `QUICK_START.md`
- [ ] Review `DATABASE_SUMMARY.md`
- [ ] Confirm database name: `hrappsprod`
- [ ] Confirm user has CREATE/DROP privileges

### Phase 2: Database Migration (10 minutes)

#### Step 2.1: Prepare Clean Database
```bash
# Option A: Drop and recreate (recommended)
mysql -u username -p << EOF
DROP DATABASE IF EXISTS hrappsprod;
CREATE DATABASE hrappsprod 
  CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;
EOF

# Option B: Delete all tables (if DROP not allowed)
mysql -u username -p hrappsprod -e "
SET FOREIGN_KEY_CHECKS = 0;
-- List and drop all tables manually
SET FOREIGN_KEY_CHECKS = 1;
"
```

#### Step 2.2: Import Schema & Data
```bash
# Import the complete SQL file
mysql -u username -p hrappsprod < hrapps_laravel_compatible_COMPLETE.sql

# Monitor import (if needed)
tail -f /var/log/mysql/error.log
```

**Expected Output:**
- No errors
- Import completes in ~30-60 seconds
- 70+ tables created
- Dummy data inserted

#### Step 2.3: Verify Import
```sql
-- Connect to database
mysql -u username -p hrappsprod

-- Check table count
SELECT COUNT(*) as table_count 
FROM information_schema.tables 
WHERE table_schema = 'hrappsprod';
-- Expected: 70+

-- Check users table
SELECT id, name, email, active FROM users;
-- Expected: 5 users

-- Check foreign keys
SELECT COUNT(*) as fk_count
FROM information_schema.KEY_COLUMN_USAGE 
WHERE table_schema = 'hrappsprod' 
AND referenced_table_name IS NOT NULL;
-- Expected: 60+

-- Check data
SELECT 
  (SELECT COUNT(*) FROM employees) as employees,
  (SELECT COUNT(*) FROM departments) as departments,
  (SELECT COUNT(*) FROM attendance) as attendance,
  (SELECT COUNT(*) FROM payslip) as payslips;
-- Expected: 5, 4, 3, 2
```

### Phase 3: Laravel Configuration (10 minutes)

#### Step 3.1: Update Environment
```bash
# Edit .env file
nano .env
```

```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hrappsprod
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Ensure these are set
APP_NAME="HRIS Application"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://your-domain.com
```

#### Step 3.2: Clear Laravel Cache
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Regenerate config cache
php artisan config:cache

# Test database connection
php artisan db:show
```

#### Step 3.3: Verify Models
```bash
# Check User model
php artisan tinker
>>> App\Models\User::count()
# Expected: 5

>>> App\Models\User::where('email', 'admin@aratechnology.id')->first()
# Should return user object

>>> exit
```

### Phase 4: Application Testing (15 minutes)

#### Step 4.1: Test Authentication
```bash
# Start development server
php artisan serve
```

**Browser Test:**
1. Navigate to `http://localhost:8000/login`
2. Login with:
   - Email: `admin@aratechnology.id`
   - Password: `Password123!`
3. Should redirect to dashboard

#### Step 4.2: Test Modules

**Employee Module:**
```
Navigate to: /employees
Expected: List of 5 employees
Check: Employee details, positions, departments
```

**Attendance Module:**
```
Navigate to: /attendance
Expected: Today's attendance (3 records)
Check: Check-in/out times, GPS coordinates
```

**Leave Module:**
```
Navigate to: /leave or /approvals
Expected: 2 leave requests (1 approved, 1 pending)
Check: Request details, approval status
```

**Payroll Module:**
```
Navigate to: /payroll
Expected: 2 payslips for November 2025
Check: Salary components, deductions, net amount
```

**KPI Module:**
```
Navigate to: /kpi
Expected: Active KPI for employee John Developer
Check: KPI items, check-ins, scores
```

---

## 6. Post-Migration Tasks

### Security Updates

#### 6.1: Change Default Passwords
```bash
php artisan tinker
```

```php
// Update admin password
$user = App\Models\User::where('email', 'admin@aratechnology.id')->first();
$user->password = Hash::make('YourNewSecurePassword123!');
$user->save();

// Repeat for other users
```

#### 6.2: Update Application Keys
```bash
# Generate new app key (if fresh install)
php artisan key:generate

# Update JWT secret (if using JWT)
php artisan jwt:secret
```

#### 6.3: Configure Email
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourserver.com
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Data Customization

#### 6.4: Update Company Information
```sql
UPDATE letter_configurations SET
  company_name = 'Your Company Name',
  company_address = 'Your Address',
  company_phone = 'Your Phone',
  company_email = 'contact@yourcompany.com',
  company_website = 'https://yourcompany.com'
WHERE id = 1;

UPDATE foundations SET
  foundation_name = 'Your Foundation',
  email = 'contact@yourcompany.com',
  address = 'Your Complete Address'
WHERE foundation_id = 'FND001';
```

#### 6.5: Configure Departments
```sql
-- Update existing departments
UPDATE departments SET
  department_name = 'Your Department Name',
  description = 'Department Description',
  check_in = '09:00:00',
  check_out = '18:00:00'
WHERE department_id = 1;

-- Add new departments
INSERT INTO departments (foundation_id, department_name, description, code, check_in, check_out, created_at, updated_at)
VALUES ('FND001', 'Sales', 'Sales Department', 'SALES', '09:00:00', '18:00:00', NOW(), NOW());
```

#### 6.6: Setup Pay Components
```sql
-- Review and adjust salary components
SELECT * FROM pay_component;

-- Update amounts for pay grades
UPDATE pay_grade_component SET
  default_amount = 8000000.00
WHERE pay_grade_id = 1 AND component_id = 1; -- Executive basic salary
```

### Maintenance Setup

#### 6.7: Schedule Backup
```bash
# Add to crontab
crontab -e
```

```cron
# Daily backup at 2 AM
0 2 * * * mysqldump -u username -p'password' hrappsprod > /backup/hrappsprod_$(date +\%Y\%m\%d).sql

# Weekly cleanup (keep 30 days)
0 3 * * 0 find /backup -name "hrappsprod_*.sql" -mtime +30 -delete
```

#### 6.8: Setup Monitoring
```sql
-- Create monitoring user (read-only)
CREATE USER 'monitor'@'localhost' IDENTIFIED BY 'secure_password';
GRANT SELECT ON hrappsprod.* TO 'monitor'@'localhost';
FLUSH PRIVILEGES;
```

---

## 7. Rollback Strategy

### If Migration Fails

#### Option 1: Restore from Backup
```bash
# Drop current database
mysql -u username -p -e "DROP DATABASE hrappsprod;"

# Recreate database
mysql -u username -p -e "CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Restore backup
mysql -u username -p hrappsprod < backup_hrappsprod_YYYYMMDD_HHMMSS.sql

# Restore .env
cp .env.backup_YYYYMMDD_HHMMSS .env
```

#### Option 2: Partial Rollback
```bash
# If only specific tables have issues
# Drop problematic tables and re-import

mysql -u username -p hrappsprod << EOF
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS table_name;
SET FOREIGN_KEY_CHECKS = 1;
EOF

# Then import specific section from SQL file
```

### If Login Issues Occur

#### Reset Admin Password
```sql
-- Direct SQL update (use known bcrypt hash)
UPDATE users SET 
  password = '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',
  active = 1
WHERE email = 'admin@aratechnology.id';
-- Password is: Password123!
```

---

## 8. Testing & Verification

### Automated Tests

#### 8.1: Database Integrity
```sql
-- Check all tables have data
SELECT 
  table_name,
  table_rows
FROM information_schema.tables
WHERE table_schema = 'hrappsprod'
AND table_rows = 0
AND table_name NOT LIKE 'cache%'
AND table_name NOT LIKE 'job%'
AND table_name NOT LIKE 'password%'
AND table_name NOT LIKE 'session%';
-- Should return few or no results

-- Check foreign key integrity
SELECT 
  constraint_name,
  table_name,
  column_name,
  referenced_table_name,
  referenced_column_name
FROM information_schema.KEY_COLUMN_USAGE
WHERE table_schema = 'hrappsprod'
AND referenced_table_name IS NOT NULL
ORDER BY table_name;
```

#### 8.2: Data Consistency
```sql
-- Users without employees
SELECT u.id, u.email
FROM users u
LEFT JOIN employees e ON u.id = e.user_id
WHERE e.employee_id IS NULL;
-- Should return 0 rows (or only system accounts)

-- Employees without positions
SELECT e.employee_id, e.fullname
FROM employees e
LEFT JOIN employee_positions ep ON e.employee_id = ep.employee_id
WHERE ep.employee_position_id IS NULL;
-- Should return 0 rows

-- Orphaned attendance records
SELECT a.attendance_id, a.employee_id
FROM attendance a
LEFT JOIN employees e ON a.employee_id = e.employee_id
WHERE e.employee_id IS NULL;
-- Should return 0 rows
```

### Manual Testing Checklist

- [ ] Login with admin account
- [ ] View employee list
- [ ] Add new employee (test form)
- [ ] Record attendance (check-in)
- [ ] Submit leave request
- [ ] Approve leave request (as manager)
- [ ] Generate payslip
- [ ] View KPI dashboard
- [ ] Check inventory
- [ ] Create task
- [ ] Generate letter
- [ ] Sign document
- [ ] Logout and re-login

### Performance Verification

```sql
-- Check table sizes
SELECT 
  table_name,
  ROUND((data_length + index_length) / 1024 / 1024, 2) AS size_mb
FROM information_schema.tables
WHERE table_schema = 'hrappsprod'
ORDER BY (data_length + index_length) DESC
LIMIT 20;

-- Check slow queries (if enabled)
SELECT * FROM mysql.slow_log 
WHERE start_time > DATE_SUB(NOW(), INTERVAL 1 HOUR)
ORDER BY query_time DESC;
```

---

## Appendix A: Test Accounts

| Email | Password | Role | Department | Access Level |
|-------|----------|------|------------|--------------|
| admin@aratechnology.id | Password123! | Administrator | IT | Full system |
| manager.it@aratechnology.id | Password123! | Manager | IT | Department + KPI |
| manager.hr@aratechnology.id | Password123! | Manager | HR | Department + KPI |
| john.dev@aratechnology.id | Password123! | Employee | IT | Self-service |
| jane.hr@aratechnology.id | Password123! | Employee | HR | Self-service |

---

## Appendix B: Troubleshooting

### Common Issues

#### Issue 1: Import Error - "Table already exists"
**Solution:**
```bash
mysql -u username -p -e "DROP DATABASE hrappsprod; CREATE DATABASE hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u username -p hrappsprod < hrapps_laravel_compatible_COMPLETE.sql
```

#### Issue 2: Foreign Key Constraint Fails
**Solution:**
```sql
-- Check which constraint is failing
SHOW ENGINE INNODB STATUS;

-- Disable foreign key checks temporarily (not recommended for production)
SET FOREIGN_KEY_CHECKS = 0;
-- Re-run import
SET FOREIGN_KEY_CHECKS = 1;
```

#### Issue 3: Login "Credentials don't match"
**Solution:**
```sql
-- Verify user exists and is active
SELECT id, email, active, password FROM users WHERE email = 'admin@aratechnology.id';

-- Check password hash format
-- Should start with $2y$12$

-- Reset if needed
UPDATE users SET 
  password = '$2y$12$LGl8.2f4yeT8FmxqJH8gvOLKhvF4FfF8lE3fLqGfFaOxNvPYqHZIa',
  active = 1
WHERE email = 'admin@aratechnology.id';
```

#### Issue 4: "SQLSTATE[42S02]: Base table or view not found"
**Solution:**
```bash
# Check if all tables imported
mysql -u username -p hrappsprod -e "SHOW TABLES;" | wc -l
# Should be 70+

# Re-import if tables are missing
mysql -u username -p hrappsprod < hrapps_laravel_compatible_COMPLETE.sql
```

---

## Appendix C: SQL File Details

### hrapps_laravel_compatible_COMPLETE.sql Structure

```
1. Header & Database Selection
   └─ USE hrappsprod;

2. Foundation Tables (Lines ~1-200)
   ├─ foundations
   ├─ departments
   └─ job_positions

3. User Management (Lines ~200-400)
   ├─ user_types
   ├─ users (Laravel standard)
   ├─ roles
   ├─ list_menu_features
   └─ user_type_roles

4. Employee Management (Lines ~400-600)
   ├─ education_levels
   ├─ employees
   ├─ employee_positions
   ├─ employee_families
   └─ pay_grade

5. Documents (Lines ~600-700)
   ├─ identity_types
   ├─ document_identity
   └─ bank_account

6. Attendance (Lines ~700-800)
   ├─ attendance
   └─ presences

7. Leave & Approvals (Lines ~800-950)
   ├─ approval_types
   ├─ category_approvals
   ├─ approval_requests
   └─ approved

8. Payroll (Lines ~950-1150)
   ├─ payroll_period
   ├─ pay_component
   ├─ pay_grade_component
   ├─ employee_pay_component
   ├─ payslip
   └─ payslip_line

9. KPI System (Lines ~1150-1600)
   └─ All 15 KPI tables

10. Additional Modules (Lines ~1600-1800)
    ├─ Inventory (3 tables)
    ├─ Incidents (1 table)
    ├─ Tasks (1 table)
    ├─ Letters (4 tables)
    └─ Signatures (2 tables)

11. Laravel System (Lines ~1800-end)
    ├─ cache
    ├─ jobs
    ├─ migrations
    └─ sessions
```

---

## Appendix D: Quick Reference Commands

### MySQL Commands
```bash
# Connect
mysql -u username -p hrappsprod

# Show tables
SHOW TABLES;

# Show table structure
DESCRIBE table_name;

# Count records
SELECT COUNT(*) FROM table_name;

# Export
mysqldump -u username -p hrappsprod > export.sql

# Import
mysql -u username -p hrappsprod < import.sql
```

### Laravel Commands
```bash
# Database info
php artisan db:show

# Run migrations
php artisan migrate

# Fresh migration
php artisan migrate:fresh

# Seed database
php artisan db:seed

# Tinker (interactive)
php artisan tinker

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

**End of Migration Strategy Document**

**Questions or Issues?**
- Check `QUICK_START.md` for immediate help
- Review `DATABASE_SUMMARY.md` for technical details
- Consult Laravel documentation: https://laravel.com/docs

**Migration Support:**
- Document Version: 3.0
- Last Updated: 2025-12-27
- Maintained by: HRIS Development Team
