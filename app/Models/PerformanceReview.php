<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    protected $fillable = [
        'employee_id',
        'reviewer_id',
        'period',
        'overall_score',
        'achievements',
        'areas_improvement',
        'goals_next_period',
        'comments',
        'status',
        'reviewed_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'overall_score' => 'decimal:2',
        'reviewed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeByPeriod($query, $period)
    {
        return $query->where('period', $period);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
