# 🔧 Update Models Guide - New Tables

Models berhasil dibuat! Sekarang perlu update dengan relationships dan properties yang benar.

---

## ✅ Models Created

```
✅ app/Models/EmployeeFamily.php
✅ app/Models/EmployeeContact.php
✅ app/Models/EmployeeDocument.php
✅ app/Models/LeaveBalance.php
⚠️  app/Models/Presence.php (already exists)
```

---

## 📝 Update Required

### 1. Update Employee Model

**File:** `app/Models/Employee.php`

Tambahkan relationships ini:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id', 'code', 'fullname', 'gender', 'place_of_birth',
        'date_of_birth', 'nik', 'npwp', 'marital_status', 'religion',
        'education_level_id', 'phone', 'email', 'address',
        'join_date', 'hire_date', 'resign_date', 'status'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'join_date' => 'date',
        'hire_date' => 'date',
        'resign_date' => 'date',
    ];

    // Existing relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function positions()
    {
        return $this->hasMany(EmployeePosition::class);
    }

    public function currentPosition()
    {
        return $this->hasOne(EmployeePosition::class)->where('is_current', 1);
    }

    // ✨ NEW RELATIONSHIPS (Add these)
    public function families()
    {
        return $this->hasMany(EmployeeFamily::class);
    }

    public function contacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }

    // Helper methods
    public function getEmergencyContact()
    {
        return $this->contacts()->where('is_primary', 1)->first();
    }

    public function getSpouse()
    {
        return $this->families()->where('relationship', 'Spouse')->first();
    }

    public function getChildren()
    {
        return $this->families()->where('relationship', 'Child')->get();
    }
}
```

---

### 2. EmployeeFamily Model

**File:** `app/Models/EmployeeFamily.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeFamily extends Model
{
    use SoftDeletes;

    protected $table = 'employee_families';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'nik', 'no_kk', 'fullname', 'relationship',
        'gender', 'place_of_birth', 'date_of_birth', 'education',
        'occupation', 'phone', 'is_dependent', 'is_emergency_contact', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'is_dependent' => 'boolean',
        'is_emergency_contact' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
```

---

### 3. EmployeeContact Model

**File:** `app/Models/EmployeeContact.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeContact extends Model
{
    protected $table = 'employee_contacts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'contact_type', 'name', 'relationship',
        'phone', 'phone_alt', 'email', 'address', 'is_primary', 'notes'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
```

---

### 4. EmployeeDocument Model

**File:** `app/Models/EmployeeDocument.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDocument extends Model
{
    use SoftDeletes;

    protected $table = 'employee_documents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'document_type', 'document_name', 'document_number',
        'file_path', 'issue_date', 'expiry_date', 'issuing_authority', 'notes'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date < now();
    }
}
```

---

### 5. LeaveBalance Model

**File:** `app/Models/LeaveBalance.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $table = 'leave_balances';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'year', 'leave_type', 'total_days',
        'used_days', 'carried_forward', 'notes'
    ];

    protected $casts = [
        'total_days' => 'decimal:2',
        'used_days' => 'decimal:2',
        'carried_forward' => 'decimal:2',
    ];

    // Note: remaining_days is a computed column in database
    protected $appends = ['remaining_days'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getRemainingDaysAttribute()
    {
        return $this->total_days - $this->used_days;
    }

    public function hasBalance()
    {
        return $this->remaining_days > 0;
    }
}
```

---

### 6. Update Presence Model (if needed)

**File:** `app/Models/Presence.php`

Pastikan ada relationships ini:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $table = 'presences';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'date', 'check_in', 'check_in_location',
        'check_in_latitude', 'check_in_longitude', 'check_in_photo',
        'check_out', 'check_out_location', 'check_out_latitude',
        'check_out_longitude', 'check_out_photo', 'work_type',
        'status', 'working_hours', 'overtime_hours', 'notes',
        'approved_by', 'approved_at'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
        'working_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
```

---

## 🧪 Testing Models

Setelah update, test relationships dengan tinker:

```bash
php artisan tinker
```

```php
// Test Employee relationships
$employee = App\Models\Employee::find(1);
$employee->families;  // Should return collection
$employee->contacts;  // Should return collection
$employee->documents; // Should return collection
$employee->presences; // Should return collection
$employee->leaveBalances; // Should return collection

// Test reverse relationships
$family = App\Models\EmployeeFamily::first();
$family->employee; // Should return Employee

// Test helper methods
$employee->getEmergencyContact();
$employee->getSpouse();
$employee->getChildren();

// Test leave balance
$balance = App\Models\LeaveBalance::where('employee_id', 1)
    ->where('year', 2025)
    ->first();
$balance->remaining_days; // Should return computed value
$balance->hasBalance(); // Should return true/false
```

---

## ✅ Verification Checklist

After updating models:
- [ ] All models have correct namespace
- [ ] All models have correct table name
- [ ] All fillable fields defined
- [ ] All casts defined for dates/booleans/decimals
- [ ] Employee model has new relationships
- [ ] New models have belongsTo Employee relationship
- [ ] Soft deletes enabled where needed
- [ ] Test with `php artisan tinker`
- [ ] No errors when accessing relationships

---

## 🚀 Quick Update Commands

```bash
# Clear cache after updating models
php artisan cache:clear
php artisan config:clear

# Run tinker to test
php artisan tinker
```

---

**Models sudah dibuat! Tinggal copy-paste code di atas ke masing-masing file model.**
