<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'transaction_date' => 'date',
        'amount'           => 'decimal:2',
        'running_balance'  => 'decimal:2',
        'is_end_of_month'  => 'boolean',
        'is_end_of_year'   => 'boolean',
    ];

    public function senderEntity()
    {
        return $this->belongsTo(FinancialEntity::class, 'sender_entity_id');
    }

    public function receiverEntity()
    {
        return $this->belongsTo(FinancialEntity::class, 'receiver_entity_id');
    }

    public function account()
    {
        return $this->belongsTo(FinancialAccount::class, 'account_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
