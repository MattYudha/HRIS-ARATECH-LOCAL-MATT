<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'employee_id',
        'comment',
        'evidence_path',
    ];

    /**
     * Get the task that owns the comment.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the employee that authored the comment.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
