<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentityType extends Model
{
    use HasFactory;

    protected $primaryKey = 'identity_type_id';

    protected $fillable = [
        'name',
    ];
}
