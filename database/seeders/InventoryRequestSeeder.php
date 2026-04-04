<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryRequest;
use App\Models\Employee;
use App\Models\Inventory;
use Carbon\Carbon;

class InventoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        $inventories = Inventory::all();
        $hrEmployee = Employee::whereHas('role', function($q){
            $q->where('title', 'HR');
        })->first();

        if ($employees->isEmpty()) return;

        $requests = [
            [
                'employee_id' => $employees->random()->id,
                'inventory_id' => null,
                'item_name' => 'MacBook Pro M3',
                'request_type' => 'new',
                'quantity' => 1,
                'reason' => 'Upgrade laptop untuk development mobile app.',
                'status' => 'pending',
            ],
            [
                'employee_id' => $employees->random()->id,
                'inventory_id' => $inventories->isNotEmpty() ? $inventories->random()->id : null,
                'item_name' => null,
                'request_type' => 'repair',
                'quantity' => 1,
                'reason' => 'Layar monitor berkedip terus menerus.',
                'status' => 'approved',
                'notes' => 'Segera diproses bagian IT.',
                'approved_by' => $hrEmployee ? $hrEmployee->id : null,
                'approved_at' => Carbon::now(),
            ],
            [
                'employee_id' => $employees->random()->id,
                'inventory_id' => $inventories->isNotEmpty() ? $inventories->random()->id : null,
                'item_name' => null,
                'request_type' => 'replacement',
                'quantity' => 1,
                'reason' => 'Mouse rusak klik kirinya tidak berfungsi.',
                'status' => 'completed',
                'notes' => 'Sudah diganti dengan unit baru.',
                'approved_by' => $hrEmployee ? $hrEmployee->id : null,
                'approved_at' => Carbon::now()->subDays(2),
            ],
            [
                'employee_id' => $employees->random()->id,
                'inventory_id' => null,
                'item_name' => 'Kursi Ergonomis',
                'request_type' => 'new',
                'quantity' => 2,
                'reason' => 'Penambahan kursi untuk staff baru.',
                'status' => 'rejected',
                'notes' => 'Anggaran bulan ini sudah habis.',
                'approved_by' => $hrEmployee ? $hrEmployee->id : null,
                'approved_at' => Carbon::now()->subDay(),
            ]
        ];

        foreach ($requests as $request) {
            InventoryRequest::create($request);
        }
    }
}
