<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeMutation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'old_department_id',
        'new_department_id',
        'old_role_id',
        'new_role_id',
        'old_salary',
        'new_salary',
        'mutation_date',
        'type',
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'mutation_date' => 'date',
        'old_salary' => 'decimal:2',
        'new_salary' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function oldDepartment()
    {
        return $this->belongsTo(Department::class, 'old_department_id');
    }

    public function newDepartment()
    {
        return $this->belongsTo(Department::class, 'new_department_id');
    }

    public function oldRole()
    {
        return $this->belongsTo(Role::class, 'old_role_id');
    }

    public function newRole()
    {
        return $this->belongsTo(Role::class, 'new_role_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
