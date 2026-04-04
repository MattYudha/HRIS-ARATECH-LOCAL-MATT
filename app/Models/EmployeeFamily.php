<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFamily extends Model
{
    protected $fillable = [
        'employee_id',
        'nik',
        'no_kk',
        'fullname',
        'relation',
        'place_of_birth',
        'date_of_birth',
        'gender',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
