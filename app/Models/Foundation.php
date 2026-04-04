<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foundation extends Model
{
    use HasFactory;

    protected $primaryKey = 'foundation_id';

    protected $fillable = [
        'foundation_name',
        'email',
        'phone',
        'address',
        'status',
    ];
}
