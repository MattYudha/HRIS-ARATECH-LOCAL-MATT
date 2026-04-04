<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentIdentity extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'identity_type_id',
        'identity_number',
        'file_name',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function identityType()
    {
        return $this->belongsTo(IdentityType::class, 'identity_type_id');
    }
}
