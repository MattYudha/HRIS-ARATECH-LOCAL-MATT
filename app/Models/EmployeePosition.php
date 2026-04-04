<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'position_id',
        'department_id',
        'start_date',
        'end_date',
        'sk_file_name',
        'sk_number',
        'base_on_salary',
        'is_supervisor',
        'pay_grade_id',
        'is_active',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_supervisor' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
