<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FinancialClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'amount'      => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    // ── Relationships ────────────────────────────────────
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }

    public function transaction()
    {
        return $this->belongsTo(\App\Models\FinancialTransaction::class, 'transaction_id');
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\FinancialAccount::class, 'account_id');
    }

    // ── Scopes ───────────────────────────────────────────
    public function scopePending($q)   { return $q->where('status', 'pending'); }
    public function scopeApproved($q)  { return $q->where('status', 'approved'); }
    public function scopeRejected($q)  { return $q->where('status', 'rejected'); }

    // ── Helpers ──────────────────────────────────────────
    public function isPending():  bool { return $this->status === 'pending'; }
    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => ucfirst($this->status),
        };
    }

    public function categoryLabel(): string
    {
        return match($this->category) {
            'transport'   => 'Transport',
            'meals'       => 'Makan & Minum',
            'operational' => 'Operasional',
            'equipment'   => 'Perlengkapan',
            'other'       => 'Lainnya',
            default       => ucfirst($this->category),
        };
    }
}
