<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterArchive extends Model
{
    protected $fillable = [
        'month',
        'year',
        'total_letters',
        'approved_letters',
        'printed_letters',
        'summary',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];
}
