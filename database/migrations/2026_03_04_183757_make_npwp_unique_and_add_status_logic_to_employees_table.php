<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Populate existing NPWP and employee_status if they are null
        $employees = DB::table('employees')->whereNull('npwp')->orWhereNull('employee_status')->get();
        foreach ($employees as $employee) {
            $updates = [];
            if (empty($employee->npwp)) {
                // Generate a dummy NPWP format: 00.000.000.0-000.000
                $updates['npwp'] = '00.' . rand(100, 999) . '.' . rand(100, 999) . '.' . rand(0, 9) . '-' . rand(100, 999) . '.000';
            }
            if (empty($employee->employee_status)) {
                $updates['employee_status'] = 'permanent'; // Default to permanent (Tetap)
            }
            
            if (!empty($updates)) {
                DB::table('employees')->where('id', $employee->id)->update($updates);
            }
        }

        // 2. Enforce unique and not-null constraints
        Schema::table('employees', function (Blueprint $table) {
            $table->string('npwp')->nullable(false)->change();
            $table->unique('npwp');
            $table->string('employee_status')->default('permanent')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['npwp']);
            $table->string('npwp')->nullable()->change();
            $table->string('employee_status')->nullable()->change();
        });
    }
};
