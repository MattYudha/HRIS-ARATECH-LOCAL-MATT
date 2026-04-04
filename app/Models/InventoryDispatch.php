<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryDispatch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventory_id', 
        'employee_id', 
        'quantity', 
        'area', 
        'room', 
        'dispatch_date', 
        'barcode', 
        'status'
    ];

    protected $casts = [
        'dispatch_date' => 'datetime',
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shipment()
    {
        return $this->morphOne(LogisticsShipment::class, 'trackable');
    }
}
