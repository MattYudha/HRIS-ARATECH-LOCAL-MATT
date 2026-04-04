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
        // 1. Rename existing roles
        DB::table('roles')->where('title', 'Power User')->update(['title' => 'Super Admin']);
        DB::table('roles')->where('title', 'HR')->update(['title' => 'HR Administrator']);
        DB::table('roles')->where('title', 'Manager')->update(['title' => 'Manager / Unit Head']);
        DB::table('roles')->where('title', 'Common Employee')->update(['title' => 'Employee']);

        // 2. Create Supervisor role if it doesn't exist
        DB::table('roles')->updateOrInsert(
            ['title' => 'Supervisor'],
            ['description' => 'Supervisor / Team Leader (Layer 1 Approval)', 'access' => json_encode(['attendance', 'knowledge_base']), 'created_at' => now(), 'updated_at' => now()]
        );

        // 3. Merge department-specific roles into "Employee"
        $employeeRoleId = DB::table('roles')->where('title', 'Employee')->value('id');
        
        if ($employeeRoleId) {
            $rolesToMerge = ['Developer', 'Sales', 'Logistik and Procurment'];
            $roleIdsToMerge = DB::table('roles')->whereIn('title', $rolesToMerge)->pluck('id');
            
            if ($roleIdsToMerge->isNotEmpty()) {
                // Update employees using these roles
                DB::table('employees')->whereIn('role_id', $roleIdsToMerge)->update(['role_id' => $employeeRoleId]);
                
                // Update mutations using these roles (old_role_id and new_role_id)
                DB::table('employee_mutations')->whereIn('old_role_id', $roleIdsToMerge)->update(['old_role_id' => $employeeRoleId]);
                DB::table('employee_mutations')->whereIn('new_role_id', $roleIdsToMerge)->update(['new_role_id' => $employeeRoleId]);
                
                // Also handle role_kpi mapping (any roles that were merged should have their KPIs mapped to Employee if not already)
                foreach ($roleIdsToMerge as $oldRoleId) {
                    $kpis = DB::table('role_kpi')->where('role_id', $oldRoleId)->get();
                    foreach ($kpis as $kpi) {
                        DB::table('role_kpi')->updateOrInsert(
                            ['role_id' => $employeeRoleId, 'kpi_id' => $kpi->kpi_id],
                            ['target_value' => $kpi->target_value, 'weight' => $kpi->weight, 'updated_at' => now()]
                        );
                    }
                }
                DB::table('role_kpi')->whereIn('role_id', $roleIdsToMerge)->delete();

                // Delete the old roles
                DB::table('roles')->whereIn('id', $roleIdsToMerge)->delete();
            }
        }
    }

    public function down(): void
    {
        // Reverting this is complex and usually not needed for this cleanup.
    }
};
