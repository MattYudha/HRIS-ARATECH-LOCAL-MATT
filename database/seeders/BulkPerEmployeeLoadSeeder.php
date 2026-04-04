<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Task;
use App\Models\LeaveRequest;
use App\Models\Inventory;
use App\Models\InventoryUsageLog;
use App\Models\Letter;
use App\Models\User;
use App\Models\KPI;
use App\Models\EmployeeKPIRecord;

class BulkPerEmployeeLoadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskPerEmployee   = 6;
        $leavePerEmployee  = 4;
        $invLogPerEmployee = 4;
        $letterPerEmployee = 4;
        $kpiPerEmployee    = 4; // double dari contoh awal (2)

        $employees = Employee::with('user', 'role')->get();
        if ($employees->isEmpty()) {
            $this->command?->warn('No employees found; skipping.');
            return;
        }

        $inventory = Inventory::first();
        if (! $inventory) {
            $inventory = Inventory::create([
                'inventory_category_id' => null,
                'name' => 'Generic Laptop',
                'code' => 'INV-GEN-001',
                'stock' => 50,
                'description' => 'Auto-generated dummy inventory for seeding',
            ]);
        }

        // Ensure at least four KPIs exist for variety
        if (KPI::count() < 4) {
            $defaultKpis = [
                ['KPI-TASK', 'Task Completion', 'Productivity', 'Completed/Assigned', 0.8, 'ratio'],
                ['KPI-ATT', 'Attendance Rate', 'Attendance', 'Present/WorkingDays', 0.9, 'ratio'],
                ['KPI-QUAL', 'Quality Score', 'Quality', 'Defect-free output', 95, 'percent'],
                ['KPI-CUST', 'Customer Satisfaction', 'Behavior', 'Survey score', 4.5, 'score'],
            ];
            foreach ($defaultKpis as [$code, $name, $cat, $formula, $target, $unit]) {
                KPI::firstOrCreate([
                    'code' => $code,
                ], [
                    'name' => $name,
                    'category' => $cat,
                    'description' => 'Auto seed KPI',
                    'formula' => $formula,
                    'target_value' => $target,
                    'min_value' => 0,
                    'max_value' => $target * 1.5,
                    'weight' => 0.2,
                    'unit' => $unit,
                    'status' => 'active',
                ]);
            }
        }
        $kpis = KPI::active()->get();

        $approverUser = User::whereHas('employee.role', function ($q) {
            $q->whereIn('title', ['HR', 'Power User', 'Manager']);
        })->first();
        $fallbackUser = User::first();

        foreach ($employees as $employee) {
            // Tasks
            for ($i = 0; $i < $taskPerEmployee; $i++) {
                Task::create([
                    'title' => 'Dummy Task '.Str::random(4)." for {$employee->fullname}",
                    'description' => 'Auto-generated task for load testing',
                    'assigned_to' => $employee->id,
                    'due_date' => Carbon::now()->addDays(rand(3, 30)),
                    'status' => collect(['pending', 'in progress', 'completed'])->random(),
                ]);
            }

            // Leave Requests
            for ($i = 0; $i < $leavePerEmployee; $i++) {
                $start = Carbon::now()->subDays(rand(0, 60));
                $end   = (clone $start)->addDays(rand(1, 5));
                LeaveRequest::create([
                    'employee_id' => $employee->id,
                    'leave_type'  => collect(['annual', 'sick', 'personal', 'maternity'])->random(),
                    'start_date'  => $start->toDateString(),
                    'end_date'    => $end->toDateString(),
                    'status'      => collect(['pending', 'approved', 'rejected'])->random(),
                ]);
            }

            // Inventory usage logs
            for ($i = 0; $i < $invLogPerEmployee; $i++) {
                $borrowed = Carbon::now()->subDays(rand(1, 45))->setTime(rand(8, 11), rand(0, 59));
                $returned = (clone $borrowed)->addDays(rand(0, 7));
                InventoryUsageLog::create([
                    'inventory_id'  => $inventory->id,
                    'employee_id'   => $employee->id,
                    'borrowed_date' => $borrowed,
                    'returned_date' => $returned,
                    'notes'         => 'Dummy usage log for testing',
                ]);
            }

            // Letters
            for ($i = 0; $i < $letterPerEmployee; $i++) {
                $letterNumber = 'TEST-'.strtoupper(Str::random(4)).'-'.($employee->id).'-'.($i+1).'-'.Str::random(3);
                Letter::create([
                    'user_id' => $employee->user?->id ?? $fallbackUser?->id,
                    'approver_id' => $approverUser?->id,
                    'letter_template_id' => null,
                    'letter_number' => $letterNumber,
                    'subject' => 'Dummy Letter for '.$employee->fullname,
                    'content' => "SURAT KETERANGAN KERJA\n\nYang bertanda tangan di bawah ini menerangkan bahwa {$employee->fullname} bekerja di PT Aratech Indonesia.",
                    'letter_type' => 'official',
                    'status' => collect(['draft','pending','approved','printed'])->random(),
                    'created_date' => Carbon::now()->toDateString(),
                    'approved_date' => Carbon::now(),
                    'printed_date' => null,
                    'notes' => 'Auto-generated dummy letter',
                    'purpose' => 'Testing',
                    'start_date' => Carbon::now()->subYears(rand(1,5))->toDateString(),
                    'end_date' => Carbon::now()->toDateString(),
                ]);
            }

            // KPI Records (unique per period to avoid constraint)
            for ($i = 0; $i < $kpiPerEmployee; $i++) {
                $period = Carbon::now()->subMonths($i)->format('Y-m');
                $kpi = $kpis->random();
                $target = $kpi->target_value ?? 100;
                $actual = $target * (0.6 + (rand(0,40)/100)); // 60%-100% of target

                EmployeeKPIRecord::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'kpi_id' => $kpi->id,
                        'period' => $period,
                    ],
                    [
                        'actual_value' => $actual,
                        'target_value' => $target,
                        'previous_value' => $target * 0.8,
                        'status' => $actual >= $target ? 'achieved' : ($actual >= $target * 0.8 ? 'warning' : 'critical'),
                        'notes' => 'Dummy KPI record for testing',
                        'calculation_method' => 'auto-seed',
                        'composite_score' => ($actual / max($target, 1)) * 100,
                        'performance_level' => $actual >= $target ? 'excellent' : ($actual >= $target*0.8 ? 'good' : 'needs_improvement'),
                    ]
                );
            }
        }

        $this->command?->info('Bulk dummy data per employee inserted.');
    }
}
