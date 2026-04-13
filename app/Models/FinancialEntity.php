<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialEntity extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    
    public function sentTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'sender_entity_id');
    }

    public function receivedTransactions()
    {
        return $this->hasMany(FinancialTransaction::class, 'receiver_entity_id');
    }
}
