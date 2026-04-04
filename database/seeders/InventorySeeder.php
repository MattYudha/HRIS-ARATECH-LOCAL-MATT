<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use Carbon\Carbon;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeEquipment = InventoryCategory::where('name', 'Office Equipment')->first();
        $officeSupplies = InventoryCategory::where('name', 'Office Supplies')->first();
        $technology = InventoryCategory::where('name', 'Technology')->first();
        $furniture = InventoryCategory::where('name', 'Furniture')->first();

        Inventory::create([
            'inventory_category_id' => $officeEquipment->id,
            'name' => 'Printer - HP LaserJet Pro',
            'description' => 'High-speed black and white printer for office use',
            'quantity' => 3,
            'location' => 'Office Room 101',
            'purchase_date' => Carbon::now()->subMonths(6),
            'status' => 'active',
        ]);

        Inventory::create([
            'inventory_category_id' => $officeEquipment->id,
            'name' => 'Projector - Sony VPL-FHZ75',
            'description' => 'Conference room projector for presentations',
            'quantity' => 2,
            'location' => 'Conference Room',
            'purchase_date' => Carbon::now()->subMonths(12),
            'status' => 'active',
        ]);

        Inventory::create([
            'inventory_category_id' => $officeSupplies->id,
            'name' => 'A4 Paper - White (500 sheets)',
            'description' => 'Standard white A4 copy paper',
            'quantity' => 50,
            'location' => 'Supply Room',
            'purchase_date' => Carbon::now()->subWeeks(2),
            'status' => 'active',
        ]);

        Inventory::create([
            'inventory_category_id' => $technology->id,
            'name' => 'Monitor - Dell 24 inch',
            'description' => 'Full HD LED monitor for workstations',
            'quantity' => 10,
            'location' => 'IT Storage',
            'purchase_date' => Carbon::now()->subMonths(3),
            'status' => 'active',
        ]);

        Inventory::create([
            'inventory_category_id' => $furniture->id,
            'name' => 'Office Chair - Ergonomic',
            'description' => 'Comfortable ergonomic office chairs',
            'quantity' => 15,
            'location' => 'Furniture Storage',
            'purchase_date' => Carbon::now()->subMonths(8),
            'status' => 'active',
        ]);
    }
}
