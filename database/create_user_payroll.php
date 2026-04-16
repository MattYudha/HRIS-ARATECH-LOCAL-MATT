<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$dept = Department::firstOrCreate(
    ['name' => 'IT'],
    ['description' => 'Information Technology', 'status' => 'active']
);

$role = Role::firstOrCreate(
    ['title' => 'Employee'],
    ['description' => 'Standard Employee']
);

$emp = Employee::firstOrCreate(
    ['email' => 'budi@aratech.id'],
    [
        'nik'             => '3175092603030007',
        'fullname'        => 'Budi Santoso',
        'phone_number'    => '081234567890',
        'address'         => 'Jl. Merdeka No. 10, Jakarta',
        'birth_date'      => '1995-06-15',
        'hire_date'       => '2023-01-01',
        'department_id'   => $dept->id,
        'role_id'         => $role->id,
        'supervisor_id'   => null,
        'status'          => 'active',
        'employee_status' => 'permanent',
        'salary'          => 8500000,
        'npwp'            => '12.345.678.9-001.000',
    ]
);

User::where('email', 'budi@aratech.id')->forceDelete();
User::create([
    'name'        => 'Budi Santoso',
    'email'       => 'budi@aratech.id',
    'password'    => Hash::make('budi123'),
    'employee_id' => $emp->id,
]);

$months = [
    [
        'period_month'        => 1,
        'period_year'         => 2026,
        'salary'              => 8500000,
        'transport_allowance' => 750000,
        'meal_allowance'      => 500000,
        'position_allowance'  => 1000000,
        'overtime_hours'      => 8,
        'overtime_amount'     => 400000,
        'performance_bonus'   => 500000,
        'attendance_bonus'    => 300000,
        'other_bonus'         => 0,
        'working_days'        => 22,
        'days_present'        => 21,
        'late_count'          => 1,
        'late_deduction'      => 50000,
        'absent_count'        => 1,
        'absent_deduction'    => 150000,
        'bpjs_kes'            => 170000,
        'bpjs_tk'             => 85000,
        'pph21'               => 245000,
        'other_deduction'     => 0,
        'penalty_amount'      => 0,
        'status'              => 'paid',
        'pay_date'            => '2026-02-01',
        'notes'               => 'Payroll Januari 2026',
    ],
    [
        'period_month'        => 2,
        'period_year'         => 2026,
        'salary'              => 8500000,
        'transport_allowance' => 750000,
        'meal_allowance'      => 500000,
        'position_allowance'  => 1000000,
        'overtime_hours'      => 4,
        'overtime_amount'     => 200000,
        'performance_bonus'   => 750000,
        'attendance_bonus'    => 300000,
        'other_bonus'         => 0,
        'working_days'        => 20,
        'days_present'        => 20,
        'late_count'          => 0,
        'late_deduction'      => 0,
        'absent_count'        => 0,
        'absent_deduction'    => 0,
        'bpjs_kes'            => 170000,
        'bpjs_tk'             => 85000,
        'pph21'               => 280000,
        'other_deduction'     => 0,
        'penalty_amount'      => 0,
        'status'              => 'paid',
        'pay_date'            => '2026-03-01',
        'notes'               => 'Payroll Februari 2026',
    ],
    [
        'period_month'        => 3,
        'period_year'         => 2026,
        'salary'              => 8500000,
        'transport_allowance' => 750000,
        'meal_allowance'      => 500000,
        'position_allowance'  => 1000000,
        'overtime_hours'      => 12,
        'overtime_amount'     => 600000,
        'performance_bonus'   => 1000000,
        'attendance_bonus'    => 300000,
        'other_bonus'         => 500000,
        'working_days'        => 21,
        'days_present'        => 21,
        'late_count'          => 0,
        'late_deduction'      => 0,
        'absent_count'        => 0,
        'absent_deduction'    => 0,
        'bpjs_kes'            => 170000,
        'bpjs_tk'             => 85000,
        'pph21'               => 350000,
        'other_deduction'     => 0,
        'penalty_amount'      => 0,
        'status'              => 'paid',
        'pay_date'            => '2026-04-01',
        'notes'               => 'Payroll Maret 2026',
    ],
];

foreach ($months as $data) {
    $totalEarnings = $data['salary']
        + $data['transport_allowance']
        + $data['meal_allowance']
        + $data['position_allowance']
        + $data['overtime_amount']
        + $data['performance_bonus']
        + $data['attendance_bonus']
        + $data['other_bonus'];

    $totalDeductions = $data['late_deduction']
        + $data['absent_deduction']
        + $data['penalty_amount']
        + $data['bpjs_kes']
        + $data['bpjs_tk']
        + $data['pph21']
        + $data['other_deduction'];

    $bonuses = $data['performance_bonus'] + $data['attendance_bonus'] + $data['other_bonus'];

    Payroll::updateOrCreate(
        [
            'employee_id'  => $emp->id,
            'period_month' => $data['period_month'],
            'period_year'  => $data['period_year'],
        ],
        array_merge($data, [
            'employee_id'      => $emp->id,
            'bonuses'          => $bonuses,
            'total_earnings'   => $totalEarnings,
            'deductions'       => $totalDeductions,
            'total_deductions' => $totalDeductions,
            'net_salary'       => $totalEarnings - $totalDeductions,
        ])
    );
}

echo "\n====================================================\n";
echo "SUKSES! Data karyawan dan payroll berhasil dibuat!\n";
echo "====================================================\n";
echo "Login Email : budi@aratech.id\n";
echo "Password    : budi123\n";
echo "Slip Gaji   : Januari, Februari, Maret 2026\n";
echo "====================================================\n";
