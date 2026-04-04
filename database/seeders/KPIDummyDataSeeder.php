<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\KPI;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KPIDummyDataSeeder extends Seeder
{
    public function run()
    {
        $employees = Employee::all();
        $kpis = KPI::where('status', 'active')->get();
        $periods = [
            Carbon::now()->format('Y-m'),
            Carbon::now()->subMonth()->format('Y-m'),
        ];

        foreach ($employees as $employee) {
            foreach ($periods as $period) {
                // Randomly choose a submission status for this employee/period
                $subStatus = ['draft', 'submitted', 'approved', 'rejected'][rand(0, 3)];
                $submittedAt = ($subStatus !== 'draft') ? now()->subDays(rand(1, 5)) : null;

                foreach ($kpis as $kpi) {
                    $target = $kpi->target_value > 0 ? $kpi->target_value : 100;
                    // Actual value between 60% and 110% of target
                    $actual = $target * (rand(60, 110) / 100);
                    
                    $achievement = ($actual / $target) * 100;
                    
                    if ($achievement >= 90) {
                        $status = 'achieved';
                        $perf = 'excellent';
                    } elseif ($achievement >= 75) {
                        $status = 'achieved';
                        $perf = 'good';
                    } elseif ($achievement >= 60) {
                        $status = 'warning';
                        $perf = 'satisfactory';
                    } else {
                        $status = 'critical';
                        $perf = 'unsatisfactory';
                    }

                    DB::table('employee_kpi_records')->updateOrInsert(
                        [
                            'employee_id' => $employee->id,
                            'kpi_id' => $kpi->id,
                            'period' => $period,
                        ],
                        [
                            'actual_value' => $actual,
                            'target_value' => $target,
                            'composite_score' => min($achievement, 100),
                            'status' => $status,
                            'performance_level' => $perf,
                            'submission_status' => $subStatus,
                            'submitted_at' => $submittedAt,
                            'notes' => 'Generated dummy data for testing',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
