<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProcurementItem extends Model
{
    protected $fillable = ['procurement_id', 'inventory_id', 'item_name', 'quantity', 'unit_price', 'subtotal'];

    public function procurement()
    {
        return $this->belongsTo(Procurement::class);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
