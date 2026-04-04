<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryUsageLog extends Model
{
    use SoftDeletes;

    protected $fillable = ['inventory_id', 'employee_id', 'borrowed_date', 'returned_date', 'notes'];

    protected $casts = [
        'borrowed_date' => 'datetime',
        'returned_date' => 'datetime',
    ];

    protected $dates = ['borrowed_date', 'returned_date'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Ensure dates are always Carbon instances
    public function getBorrowedDateAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value;
        }
        return $value ? \Carbon\Carbon::parse($value) : null;
    }

    public function getReturnedDateAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value;
        }
        return $value ? \Carbon\Carbon::parse($value) : null;
    }
}
