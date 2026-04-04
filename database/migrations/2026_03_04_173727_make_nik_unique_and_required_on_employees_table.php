<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clarify: 1265 Data truncated usually means unexpected data format for non-null
        // or strict mode issues. We ensure all NIKs are trimmed and non-empty.
        $employees = DB::table('employees')->get();
        foreach ($employees as $employee) {
            if (empty(trim((string)$employee->nik))) {
                DB::table('employees')->where('id', $employee->id)->update([
                    'nik' => '320' . str_pad($employee->id, 13, '0', STR_PAD_LEFT)
                ]);
            } else {
                DB::table('employees')->where('id', $employee->id)->update([
                    'nik' => trim($employee->nik)
                ]);
            }
        }

        Schema::table('employees', function (Blueprint $table) {
            $table->string('nik')->nullable(false)->change();
            $table->unique('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropUnique(['nik']);
            $table->string('nik')->nullable()->change();
        });
    }
};
