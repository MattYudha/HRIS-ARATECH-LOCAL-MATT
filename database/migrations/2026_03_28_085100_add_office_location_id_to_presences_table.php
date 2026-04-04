<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('presences', 'office_location_id')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->foreignId('office_location_id')
                    ->nullable()
                    ->after('employee_id')
                    ->constrained('office_locations')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('presences', 'office_location_id') || !Schema::hasColumn('employees', 'office_location_id')) {
            return;
        }

        $legacyWfoPresences = DB::table('presences')
            ->join('employees', 'presences.employee_id', '=', 'employees.id')
            ->where('presences.work_type', 'WFO')
            ->whereNull('presences.office_location_id')
            ->whereNotNull('employees.office_location_id')
            ->select('presences.id', 'employees.office_location_id')
            ->get();

        foreach ($legacyWfoPresences as $presence) {
            DB::table('presences')
                ->where('id', $presence->id)
                ->update(['office_location_id' => $presence->office_location_id]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('presences', 'office_location_id')) {
            Schema::table('presences', function (Blueprint $table) {
                $table->dropConstrainedForeignId('office_location_id');
            });
        }
    }
};
