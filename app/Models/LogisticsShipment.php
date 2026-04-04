<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogisticsShipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'trackable_id', 
        'trackable_type', 
        'tracking_number', 
        'carrier', 
        'origin', 
        'destination', 
        'status', 
        'estimated_arrival', 
        'actual_arrival'
    ];

    protected $casts = [
        'estimated_arrival' => 'datetime',
        'actual_arrival' => 'datetime',
    ];

    public function trackable()
    {
        return $this->morphTo();
    }
}
