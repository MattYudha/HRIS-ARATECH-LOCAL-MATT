<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'contact_person', 'email', 'phone', 'address', 'status'];

    public function procurements()
    {
        return $this->hasMany(Procurement::class);
    }
}
