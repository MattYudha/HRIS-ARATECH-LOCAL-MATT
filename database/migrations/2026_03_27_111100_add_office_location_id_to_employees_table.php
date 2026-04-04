<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'office_location_id')) {
                $table->foreignId('office_location_id')
                    ->nullable()
                    ->after('department_id')
                    ->constrained('office_locations')
                    ->nullOnDelete();
            }
        });

        $defaultOfficeLocationId = DB::table('office_locations')->orderBy('id')->value('id');
        if ($defaultOfficeLocationId) {
            DB::table('employees')
                ->whereNull('office_location_id')
                ->update(['office_location_id' => $defaultOfficeLocationId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'office_location_id')) {
                $table->dropConstrainedForeignId('office_location_id');
            }
        });
    }
};
