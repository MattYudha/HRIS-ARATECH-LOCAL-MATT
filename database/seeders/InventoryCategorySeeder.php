<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;

class InventoryCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InventoryCategory::create([
            'name' => 'Office Equipment',
            'description' => 'Large office equipment like printers, projectors, and fax machines',
        ]);

        InventoryCategory::create([
            'name' => 'Office Supplies',
            'description' => 'General office supplies like paper, pens, and folders',
        ]);

        InventoryCategory::create([
            'name' => 'Furniture',
            'description' => 'Office furniture such as chairs, desks, and cabinets',
        ]);

        InventoryCategory::create([
            'name' => 'Technology',
            'description' => 'Technology items including computers, monitors, and cables',
        ]);

        InventoryCategory::create([
            'name' => 'Tools',
            'description' => 'Various tools for maintenance and repairs',
        ]);
    }
}
