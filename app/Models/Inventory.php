<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventory_category_id', 
        'item_type',
        'name', 
        'description', 
        'quantity', 
        'location', 
        'area',
        'room',
        'purchase_date', 
        'status', 
        'min_stock_threshold', 
        'image_path'
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    protected $dates = ['purchase_date'];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }

    public function usageLogs()
    {
        return $this->hasMany(InventoryUsageLog::class);
    }

    public function dispatches()
    {
        return $this->hasMany(InventoryDispatch::class);
    }

    public function procurementItems()
    {
        return $this->hasMany(ProcurementItem::class);
    }

    // Ensure purchase_date is always a Carbon instance
    public function getPurchaseDateAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            return $value;
        }
        return $value ? \Carbon\Carbon::parse($value) : null;
    }
}
