<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Presence;
use App\Models\Task;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayrollDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        if ($employees->isEmpty()) {
            $this->command?->warn('No employees found; skipping payroll dummy data.');
            return;
        }

        $now = Carbon::now();
        $months = [
            ['month' => 2, 'year' => 2026, 'status' => 'paid'],
            ['month' => 3, 'year' => 2026, 'status' => 'draft'],
        ];

        foreach ($employees as $employee) {
            foreach ($months as $period) {
                $this->generateDataForEmployee($employee, $period['month'], $period['year'], $period['status']);
            }
        }

        $this->command?->info('✓ Created realistic presence, tasks, and payroll data for ' . $employees->count() . ' employees.');
    }

    private function generateDataForEmployee(Employee $employee, int $month, int $year, string $payrollStatus): void
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // If it's the current month, only go up to today
        if ($year == Carbon::now()->year && $month == Carbon::now()->month) {
            $endDate = Carbon::now();
        }

        $daysPresent = 0;
        $lateCount = 0;
        $absentCount = 0;
        $workDays = 0;

        // 1. Generate Presence Records
        $date = $startDate->copy();
        while ($date->lte($endDate)) {
            if (!$date->isWeekend()) {
                $workDays++;
                
                // 90% chance of being present
                $rand = rand(1, 100);
                if ($rand <= 90) {
                    $daysPresent++;
                    $status = 'present';
                    
                    // 20% chance of being late
                    $isLate = rand(1, 100) <= 20;
                    if ($isLate) {
                        $lateCount++;
                        $checkIn = $date->copy()->setTime(8, rand(31, 60), 0);
                    } else {
                        $checkIn = $date->copy()->setTime(8, rand(0, 30), 0);
                    }
                    
                    $checkOut = $date->copy()->setTime(17, rand(0, 60), 0);

                    Presence::updateOrCreate(
                        ['employee_id' => $employee->id, 'date' => $date->toDateString()],
                        [
                            'check_in' => $checkIn,
                            'check_out' => $checkOut,
                            'status' => $status,
                            'work_type' => collect(['WFO', 'WFH', 'WFA'])->random(),
                            'latitude' => -6.2 + rand(-50,50)/1000,
                            'longitude' => 106.8 + rand(-50,50)/1000,
                        ]
                    );
                } else {
                    $absentCount++;
                    Presence::updateOrCreate(
                        ['employee_id' => $employee->id, 'date' => $date->toDateString()],
                        [
                            'status' => 'absent',
                            'check_in' => null,
                            'check_out' => null,
                        ]
                    );
                }
            }
            $date->addDay();
        }

        // 2. Generate Tasks
        if (rand(1, 100) <= 70) { // 70% chance of having tasks this month
            $taskCount = rand(2, 5);
            for ($i = 0; $i < $taskCount; $i++) {
                Task::create([
                    'employee_id' => $employee->id,
                    'title' => 'Monthly Task ' . ($i + 1) . ' for ' . $startDate->format('M Y'),
                    'description' => 'Detailed description for task ' . ($i + 1),
                    'status' => collect(['pending', 'in-progress', 'completed'])->random(),
                    'priority' => collect(['low', 'medium', 'high'])->random(),
                    'due_date' => $startDate->copy()->addDays(rand(5, 25)),
                ]);
            }
        }

        // 3. Create Payroll Record
        $baseSalary = $employee->salary ?? 5000000;
        
        // Random allowances
        $transportAllowance = 500000;
        $mealAllowance = 500000;
        $positionAllowance = rand(0, 5) * 500000;
        
        // Overtime (Lembur)
        $overtimeHours = rand(0, 20);
        $overtimeRate = ($baseSalary / 173) * 1.5; // Formula standar lembur
        $overtimeAmount = $overtimeHours * $overtimeRate;
        
        // Bonus (Performance, Attendance, etc.)
        $performanceBonus = (rand(1, 100) > 80) ? rand(500000, 2000000) : 0;
        $attendanceBonus = ($absentCount == 0 && $lateCount == 0) ? 500000 : 0;
        $otherBonus = 0;
        
        // Deductions
        $lateDeduction = $lateCount * 50000; // 50rb per telat
        $absentDeduction = $absentCount * ($baseSalary / 22); // Potong gaji harian
        $penaltyAmount = (rand(1, 100) > 90) ? rand(100000, 500000) : 0;
        $penaltyNotes = $penaltyAmount > 0 ? 'Pelanggaran disiplin ringan' : null;
        
        // BPJS & PPh21 (Rough estimate)
        $bpjsKes = $baseSalary * 0.01;
        $bpjsTk = $baseSalary * 0.02;
        $pph21 = $baseSalary * 0.05;
        
        $payroll = Payroll::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'period_month' => $month,
                'period_year' => $year,
            ],
            [
                'salary' => $baseSalary,
                'transport_allowance' => $transportAllowance,
                'meal_allowance' => $mealAllowance,
                'position_allowance' => $positionAllowance,
                'overtime_hours' => $overtimeHours,
                'overtime_amount' => $overtimeAmount,
                'performance_bonus' => $performanceBonus,
                'attendance_bonus' => $attendanceBonus,
                'other_bonus' => $otherBonus,
                'bonus_notes' => $performanceBonus > 0 ? 'Bonus performa bulanan' : null,
                'working_days' => $workDays,
                'days_present' => $daysPresent,
                'late_count' => $lateCount,
                'late_deduction' => $lateDeduction,
                'absent_count' => $absentCount,
                'absent_deduction' => $absentDeduction,
                'penalty_amount' => $penaltyAmount,
                'penalty_notes' => $penaltyNotes,
                'bpjs_kes' => $bpjsKes,
                'bpjs_tk' => $bpjsTk,
                'pph21' => $pph21,
                'status' => $payrollStatus,
                'pay_date' => $payrollStatus === 'paid' ? $endDate->copy()->addDays(1) : null,
            ]
        );

        $payroll->calculateNetSalary();
        $payroll->save();
    }
}
