<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Procurement extends Model
{
    use SoftDeletes;

    protected $fillable = ['vendor_id', 'employee_id', 'po_number', 'order_date', 'status', 'total_amount', 'notes'];

    protected $casts = [
        'order_date' => 'date',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function requester()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function items()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    public function shipment()
    {
        return $this->morphOne(LogisticsShipment::class, 'trackable');
    }
}
