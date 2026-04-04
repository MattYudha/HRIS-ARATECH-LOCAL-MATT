<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'content',
        'type',
        'is_active',
    ];

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }
}
