<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Presence;

class BulkPresenceSeeder extends Seeder
{
    public function run(): void
    {
        $daysBack = 45; // ~1.5 bulan
        $employees = Employee::all();
        if ($employees->isEmpty()) {
            $this->command?->warn('No employees found; skipping presences.');
            return;
        }

        $statuses = ['present', 'present', 'present', 'late', 'absent', 'leave']; // bias ke present
        $workTypes = ['WFO', 'WFH', 'WFA'];

        foreach ($employees as $employee) {
            for ($d = 0; $d < $daysBack; $d++) {
                $date = Carbon::today()->subDays($d);

                // Weekend: tetap isi tapi boleh absent/leave/present
                $status = $date->isWeekend()
                    ? collect(['absent', 'leave', 'present'])->random()
                    : collect($statuses)->random();

                $checkIn = $date->copy()->setTime(rand(7, 9), rand(0, 59));
                $checkOut = $date->copy()->setTime(rand(16, 19), rand(0, 59));

                Presence::updateOrCreate(
                    [
                        'employee_id' => $employee->id,
                        'date' => $date->toDateString(),
                    ],
                    [
                        'latitude' => -6.2 + rand(-50,50)/1000,
                        'longitude' => 106.8 + rand(-50,50)/1000,
                        'work_type' => collect($workTypes)->random(),
                        'check_in' => $checkIn,
                        'check_out' => $checkOut,
                        'status' => $status,
                    ]
                );
            }
        }

        $this->command?->info('Bulk presence data inserted for last '.$daysBack.' days with work_type wfo/wfh/wfa.');
    }
}
