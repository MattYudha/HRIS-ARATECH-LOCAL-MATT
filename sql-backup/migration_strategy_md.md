# Strategi Migrasi Database yang Aman

**7 Phase migration dengan minimal downtime dan zero data loss**

> ✓ Semua nama tabel dan field menggunakan lowercase  
> 📅 Total Duration: 8-12 minggu  
> 🛡️ Rollback ready setiap fase

---

## Phase 1: Persiapan & Planning
**Duration:** 1-2 minggu  
**Risk:** Low

### 1.1 Backup Complete
**Description:** Backup database production dan semua file code

**Commands:**
```bash
mysqldump -u root -p hrappsprod > backup_before_migration_$(date +%Y%m%d).sql
tar -czf code_backup_$(date +%Y%m%d).tar.gz /path/to/project
```

**Checklist:**
- [ ] Database backup berhasil
- [ ] Code repository ter-backup
- [ ] Dokumentasi struktur lama tersimpan
- [ ] Test restore backup berhasil

### 1.2 Create Migration Branch
**Description:** Buat branch khusus untuk migrasi

**Commands:**
```bash
git checkout -b migration/gohr2-adoption
git checkout -b hotfix/production-support
```

**Checklist:**
- [ ] Branch migration dibuat
- [ ] Branch hotfix untuk production ready
- [ ] Team sudah informed

### 1.3 Audit & Mapping
**Description:** Audit semua dependency code ke database

**Commands:**
```bash
# Cari semua query yang menggunakan tabel lama
grep -r 'employees' app/
grep -r 'DB::table' app/
grep -r 'presences' app/
```

**Checklist:**
- [ ] List semua Model yang terpengaruh
- [ ] List semua Query Builder yang perlu diubah
- [ ] List semua API endpoint yang terpengaruh
- [ ] Dokumentasi mapping field lama ke baru

---

## Phase 2: Parallel Development
**Duration:** 2-4 minggu  
**Risk:** Low

### 2.1 Create New Schema (Parallel)
**Description:** Buat schema baru tanpa menghapus yang lama

**Commands:**
```bash
# Schema sumber: combined_hris_prod.sql (DBML-like) -> import file: hrapps_prod-fix.sql
# (Pastikan hrapps_prod-fix.sql sudah ada di server / Cloudpanel File Manager)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS hrappsprod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -u root -p hrappsprod < hrapps_prod-fix.sql
mysql -u root -p hrappsprod -e "SHOW TABLES;"
```

**Checklist:**
- [ ] Database hrappsprod tersedia
- [ ] Schema berhasil di-import dari hrapps_prod-fix.sql
- [ ] Semua tabel berhasil dibuat
- [ ] FK & constraint tidak error saat import

### 2.2 Develop New Models
**Description:** Buat Model baru untuk struktur baru

**Commands:**
```bash
php artisan make:model EmployeeV2
php artisan make:model EmployeePosition
php artisan make:model PayrollPeriod
```

**Checklist:**
- [ ] Model baru dengan namespace terpisah (App\Models\V2)
- [ ] Relationship sudah defined
- [ ] Accessor & Mutator sudah dibuat
- [ ] Belum digunakan di production code

### 2.3 Create Data Sync Scripts
**Description:** Script untuk sync data lama ke struktur baru

**Commands:**
```bash
php artisan make:command SyncEmployeesToV2
php artisan make:command SyncAttendanceToV2
```

**Checklist:**
- [ ] Script bisa dijalankan berulang (idempotent)
- [ ] Ada logging untuk tracking progress
- [ ] Ada error handling yang proper
- [ ] Test dengan sample data

---

## Phase 3: Data Migration (Staging)
**Duration:** 1 minggu  
**Risk:** Medium

### 3.1 Migrate Data ke Staging
**Description:** Copy & transform data dari struktur lama ke baru

**Commands:**
```bash
php artisan migrate:employees-to-v2 --env=staging
php artisan migrate:verify-data --env=staging
```

**Checklist:**
- [ ] Data employees ter-migrate dengan benar
- [ ] Data relationship intact (positions, departments)
- [ ] Payroll data ter-convert dengan benar
- [ ] KPI records ter-migrate
- [ ] Verification script passed

### 3.2 Data Validation
**Description:** Validasi integritas data hasil migrasi

**Commands:**
```bash
php artisan validate:migration --table=employees
php artisan compare:counts old_vs_new
```

**Checklist:**
- [ ] Row count matching (old vs new)
- [ ] Foreign key integrity check passed
- [ ] Data type conversion correct
- [ ] No data loss detected
- [ ] Sample data spot-check manual

---

## Phase 4: Code Refactoring
**Duration:** 2-3 minggu  
**Risk:** Medium

### 4.1 Refactor Bertahap (Feature by Feature)
**Description:** Update code menggunakan Adapter Pattern atau Feature Flag

**Commands:**
```php
// Gunakan feature flag
if (config('feature.use_new_schema')) {
    // Use new models
} else {
    // Use old models
}
```

**Checklist:**
- [ ] Module HR - Employee management updated
- [ ] Module Attendance updated
- [ ] Module Payroll updated
- [ ] Module KPI updated
- [ ] API endpoints tested
- [ ] Unit tests updated & passed

### 4.2 Create Adapter Layer (Temporary)
**Description:** Layer untuk backward compatibility

**Commands:**
```php
// Example Adapter
class EmployeeAdapter {
    public static function find($id) {
        if (useNewSchema()) {
            return EmployeeV2::with('position')->find($id);
        }
        return Employee::find($id);
    }
}
```

**Checklist:**
- [ ] Adapter untuk semua affected models
- [ ] API response format tetap konsisten
- [ ] Frontend tidak perlu perubahan (jika mungkin)
- [ ] Legacy code masih berfungsi

---

## Phase 5: Testing Comprehensif
**Duration:** 1-2 minggu  
**Risk:** Medium

### 5.1 Testing Menyeluruh
**Description:** Test semua fitur dengan struktur baru

**Commands:**
```bash
php artisan test
php artisan test --filter=Employee
npm run test:e2e
```

**Checklist:**
- [ ] Unit tests passed (100%)
- [ ] Integration tests passed
- [ ] E2E tests passed
- [ ] Performance tests acceptable
- [ ] Load testing passed
- [ ] UAT dengan user key passed

### 5.2 Security & Permission Check
**Description:** Pastikan tidak ada security holes

**Checklist:**
- [ ] Permission & role masih berfungsi
- [ ] Data isolation antar user/foundation OK
- [ ] Audit log masih tercatat
- [ ] Sensitive data tetap encrypted

---

## Phase 6: Deployment Production
**Duration:** 1 hari + monitoring  
**Risk:** High

### 6.1 Maintenance Window
**Description:** Deploy saat low traffic dengan downtime minimal

**Commands:**
```bash
# Enable maintenance mode
php artisan down --secret='migration-2024'

# Run migration
php artisan migrate:to-v2-production

# Switch schema
php artisan config:cache
php artisan schema:switch --to=v2

# Disable maintenance
php artisan up
```

**Checklist:**
- [ ] Tentukan waktu maintenance (malam/weekend)
- [ ] Notifikasi ke semua user H-3
- [ ] Team standby untuk rollback
- [ ] Monitoring tools ready
- [ ] Rollback plan tested

### 6.2 Post-Deployment Monitoring
**Description:** Monitor intensive 24-48 jam pertama

**Checklist:**
- [ ] Error rate normal (<1%)
- [ ] Response time acceptable
- [ ] Database performance OK
- [ ] No data corruption detected
- [ ] User feedback positive
- [ ] Backup fresh tersedia

---

## Phase 7: Cleanup & Optimization
**Duration:** 1-2 minggu  
**Risk:** Low

### 7.1 Cleanup Old Schema
**Description:** Hapus schema lama setelah 2-4 minggu stable

**Commands:**
```bash
# Backup dulu sebelum hapus
mysqldump old_tables > final_backup.sql

# Rename untuk archive
RENAME TABLE employees TO _archived_employees;

# Hapus setelah yakin (1-2 bulan)
DROP TABLE _archived_employees;
```

**Checklist:**
- [ ] Production stable minimal 2 minggu
- [ ] Tidak ada bug major
- [ ] Final backup created
- [ ] Old code removed dari repository

### 7.2 Code Cleanup
**Description:** Hapus adapter layer dan dead code

**Commands:**
```bash
# Remove feature flags
# Remove adapter classes
# Remove old models
git branch -D migration/gohr2-adoption
```

**Checklist:**
- [ ] Feature flags removed
- [ ] Adapter layer removed
- [ ] Old models & migrations removed
- [ ] Documentation updated
- [ ] Code review completed

---

## Impact Analysis - Area yang Terpengaruh

### 1. Models & Eloquent
**Impact:** HIGH

**Changes Required:**
- Ubah table name di Model (semua lowercase)
- Update relationships (hasMany, belongsTo)
- Ubah fillable & guarded fields
- Update accessor & mutator
- Update casts untuk data types baru

**Example:**
```php
// Before
class Employee extends Model {
    protected $table = 'employees';
    public function role() {
        return $this->belongsTo(Role::class);
    }
}

// After (lowercase table name)
class Employee extends Model {
    protected $table = 'employees';
    public function positions() {
        return $this->hasMany(EmployeePosition::class, 'employee_id');
    }
    public function currentPosition() {
        return $this->hasOne(EmployeePosition::class)
            ->whereNull('end_date')
            ->latest('start_date');
    }
}
```

### 2. Controllers & Services
**Impact:** MEDIUM-HIGH

**Changes Required:**
- Update query untuk join table baru
- Refactor logic yang assume single position
- Update validation rules
- Handle new relationships
- Update response transformers

**Example:**
```php
// Before
$employee = Employee::with('role', 'department')->find($id);

// After (lowercase table references)
$employee = Employee::with([
    'currentPosition.position',
    'currentPosition.department',
    'user'
])->find($id);
```

### 3. Database Queries
**Impact:** HIGH

**Changes Required:**
- Update semua raw queries (gunakan lowercase)
- Update DB::table() calls
- Refactor joins untuk multi-table
- Update group by & aggregations
- Fix subqueries

**Example:**
```php
// Before
DB::table('employees')
    ->join('roles', 'employees.role_id', '=', 'roles.id')
    ->select('employees.*', 'roles.title')
    ->get();

// After (all lowercase)
DB::table('employees')
    ->join('employee_positions', function($join) {
        $join->on('employees.employee_id', '=', 'employee_positions.employee_id')
             ->whereNull('employee_positions.end_date');
    })
    ->join('job_positions', 'employee_positions.position_id', '=', 'job_positions.position_id')
    ->select('employees.*', 'job_positions.title')
    ->get();
```

### 4. API Responses
**Impact:** MEDIUM

**Changes Required:**
- Update API Resources/Transformers
- Handle nested relationships
- Maintain backward compatibility jika perlu
- Update API documentation
- Version API jika breaking changes

**Example:**
```php
// Gunakan API Resources untuk consistency
class EmployeeResource extends JsonResource {
    public function toArray($request) {
        return [
            'id' => $this->employee_id,
            'name' => $this->fullname,
            'position' => new PositionResource(
                $this->whenLoaded('currentPosition')
            ),
            'department' => $this->currentPosition->department->name ?? null,
        ];
    }
}
```

### 5. Frontend/Views
**Impact:** LOW-MEDIUM

**Changes Required:**
- Update field names di forms
- Update table columns
- Handle new nested data structure
- Update validation messages

**Example:**
```blade
<!-- Before -->
<td>{{ $employee->role->title }}</td>

<!-- After -->
<td>{{ $employee->currentPosition->position->title ?? '-' }}</td>
```

### 6. Seeders & Factories
**Impact:** HIGH

**Changes Required:**
- Update semua seeders (lowercase table names)
- Update factory definitions
- Create new seeders untuk table baru
- Update test data generation

**Example:**
```php
// Create new seeder
class EmployeePositionSeeder extends Seeder {
    public function run() {
        Employee::all()->each(function($emp) {
            EmployeePosition::create([
                'employee_id' => $emp->employee_id,
                'position_id' => $emp->old_role_id,
                'start_date' => $emp->hire_date,
                // ...
            ]);
        });
    }
}
```

---

## Rollback Plan (Safety Net)

### Scenario 1: Critical bug ditemukan dalam 1 jam
**Action:** Immediate rollback

**Steps:**
1. `php artisan down`
2. `git checkout previous-stable-tag`
3. `php artisan config:cache`
4. Switch database connection ke old schema
5. `php artisan up`
6. Monitor error rate

### Scenario 2: Data corruption terdeteksi
**Action:** Restore dari backup

**Steps:**
1. Stop application
2. Restore database dari backup pre-migration
3. Verify data integrity
4. Deploy old code version
5. Restart application

### Scenario 3: Performance degradation significant
**Action:** Analyze & fix atau rollback

**Steps:**
1. Enable query logging
2. Identify slow queries
3. Add missing indexes
4. If can't fix in 2 hours: rollback

---

## Best Practices & Tips

### ✅ DO
- Gunakan lowercase untuk semua tabel/field
- Test di staging sebelum production
- Backup sebelum setiap fase
- Deploy saat traffic rendah
- Monitor intensive 48 jam pertama
- Dokumentasi setiap perubahan
- Communication ke team & users

### ❌ DON'T
- Campur huruf besar kecil (case-sensitive)
- Langsung hapus tabel lama
- Skip testing phase
- Deploy Friday sore/weekend
- Lupa backup sebelum migrate
- Rush implementation
- Ignore warning signs

---

## Summary

### Timeline Overview
| Phase | Duration | Risk Level | Critical Activities |
|-------|----------|------------|---------------------|
| Phase 1 | 1-2 minggu | Low | Backup, Planning, Audit |
| Phase 2 | 2-4 minggu | Low | Parallel Development |
| Phase 3 | 1 minggu | Medium | Data Migration Staging |
| Phase 4 | 2-3 minggu | Medium | Code Refactoring |
| Phase 5 | 1-2 minggu | Medium | Testing |
| Phase 6 | 1 hari + monitoring | High | Production Deployment |
| Phase 7 | 1-2 minggu | Low | Cleanup |
| **Total** | **8-12 minggu** | - | - |

### Key Success Factors
1. ✅ Thorough backup strategy
2. ✅ Parallel development approach
3. ✅ Comprehensive testing
4. ✅ Clear rollback plan
5. ✅ Team communication
6. ✅ Staged deployment
7. ✅ Post-deployment monitoring

---

**Remember:** Migrasi database adalah proses yang critical. Selalu prioritaskan keamanan data dan stabilitas sistem dibanding kecepatan implementasi.