<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payroll Configuration
    |--------------------------------------------------------------------------
    */

    // Denda keterlambatan per kejadian
    'late_penalty_per_incident' => env('PAYROLL_LATE_PENALTY', 50000),

    // Potongan absen = gaji harian × multiplier × jumlah absen
    'absent_penalty_multiplier' => env('PAYROLL_ABSENT_MULTIPLIER', 1.0),

    // Lembur rate: gaji per jam × multiplier
    'overtime_rate_multiplier' => env('PAYROLL_OVERTIME_MULTIPLIER', 1.5),

    // BPJS Kesehatan (employee share = 1%)
    'bpjs_kes_employee_rate' => env('PAYROLL_BPJS_KES_RATE', 0.01),

    // BPJS Ketenagakerjaan (employee share = 2%)
    'bpjs_tk_employee_rate' => env('PAYROLL_BPJS_TK_RATE', 0.02),

    // Standard working days per month
    'default_working_days' => env('PAYROLL_WORKING_DAYS', 22),

    // Standard working hours per day
    'working_hours_per_day' => env('PAYROLL_HOURS_PER_DAY', 8),

    // Default allowances (can be overridden per payroll)
    'default_transport_allowance' => env('PAYROLL_TRANSPORT_ALLOWANCE', 500000),
    'default_meal_allowance' => env('PAYROLL_MEAL_ALLOWANCE', 500000),
];
