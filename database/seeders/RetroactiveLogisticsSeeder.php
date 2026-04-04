<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Procurement;
use App\Models\InventoryDispatch;
use App\Models\LogisticsShipment;

class RetroactiveLogisticsSeeder extends Seeder
{
    public function run()
    {
        // 1. Generate for Procurements (Ordered or Received)
        $procurements = Procurement::whereIn('status', ['ordered', 'received'])->get();
        foreach ($procurements as $proc) {
            LogisticsShipment::firstOrCreate(
                [
                    'trackable_id' => $proc->id,
                    'trackable_type' => Procurement::class,
                ],
                [
                    'origin' => $proc->vendor->address ?? $proc->vendor->name ?? 'Vendor Address',
                    'destination' => 'Warehouse / Hub Utama',
                    'status' => $proc->status === 'received' ? 'delivered' : 'pending',
                    'estimated_arrival' => $proc->order_date ? $proc->order_date->addDays(3) : now(),
                ]
            );
        }

        // 2. Generate for Inventory Dispatches
        $dispatches = InventoryDispatch::all();
        foreach ($dispatches as $disp) {
            LogisticsShipment::firstOrCreate(
                [
                    'trackable_id' => $disp->id,
                    'trackable_type' => InventoryDispatch::class,
                ],
                [
                    'origin' => 'Warehouse / Central Hub',
                    'destination' => ($disp->area ?? '-') . ' / ' . ($disp->room ?? '-'),
                    'status' => 'delivered', // Assuming dispatched items reached their destination area
                    'estimated_arrival' => $disp->dispatch_date,
                    'actual_arrival' => $disp->dispatch_date,
                ]
            );
        }
    }
}
