<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payroll', function (Blueprint $table) {
            // Period tracking
            $table->unsignedTinyInteger('period_month')->nullable()->after('employee_id');
            $table->year('period_year')->nullable()->after('period_month');

            // Earnings breakdown
            $table->decimal('transport_allowance', 15, 2)->default(0)->after('salary');
            $table->decimal('meal_allowance', 15, 2)->default(0)->after('transport_allowance');
            $table->decimal('position_allowance', 15, 2)->default(0)->after('meal_allowance');
            $table->decimal('overtime_hours', 8, 2)->default(0)->after('position_allowance');
            $table->decimal('overtime_amount', 15, 2)->default(0)->after('overtime_hours');
            $table->decimal('performance_bonus', 15, 2)->default(0)->after('overtime_amount');
            $table->decimal('attendance_bonus', 15, 2)->default(0)->after('performance_bonus');
            $table->decimal('other_bonus', 15, 2)->default(0)->after('attendance_bonus');
            $table->text('bonus_notes')->nullable()->after('other_bonus');
            $table->decimal('total_earnings', 15, 2)->default(0)->after('bonus_notes');

            // Attendance data
            $table->unsignedInteger('working_days')->default(0)->after('total_earnings');
            $table->unsignedInteger('days_present')->default(0)->after('working_days');
            $table->unsignedInteger('late_count')->default(0)->after('days_present');
            $table->decimal('late_deduction', 15, 2)->default(0)->after('late_count');
            $table->unsignedInteger('absent_count')->default(0)->after('late_deduction');
            $table->decimal('absent_deduction', 15, 2)->default(0)->after('absent_count');

            // Penalty / Denda
            $table->decimal('penalty_amount', 15, 2)->default(0)->after('absent_deduction');
            $table->text('penalty_notes')->nullable()->after('penalty_amount');

            // Standard deductions
            $table->decimal('bpjs_kes', 15, 2)->default(0)->after('penalty_notes');
            $table->decimal('bpjs_tk', 15, 2)->default(0)->after('bpjs_kes');
            $table->decimal('pph21', 15, 2)->default(0)->after('bpjs_tk');
            $table->decimal('other_deduction', 15, 2)->default(0)->after('pph21');
            $table->text('deduction_notes')->nullable()->after('other_deduction');

            // Summary
            $table->decimal('total_deductions', 15, 2)->default(0)->after('deduction_notes');
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft')->after('net_salary');
            $table->text('notes')->nullable()->after('pay_date');
        });
    }

    public function down(): void
    {
        Schema::table('payroll', function (Blueprint $table) {
            $table->dropColumn([
                'period_month', 'period_year',
                'transport_allowance', 'meal_allowance', 'position_allowance',
                'overtime_hours', 'overtime_amount',
                'performance_bonus', 'attendance_bonus', 'other_bonus', 'bonus_notes',
                'total_earnings',
                'working_days', 'days_present',
                'late_count', 'late_deduction',
                'absent_count', 'absent_deduction',
                'penalty_amount', 'penalty_notes',
                'bpjs_kes', 'bpjs_tk', 'pph21',
                'other_deduction', 'deduction_notes',
                'total_deductions', 'status', 'notes',
            ]);
        });
    }
};
