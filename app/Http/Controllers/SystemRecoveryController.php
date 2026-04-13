<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Department;
use App\Constants\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

/**
 * UTILITY CONTROLLER: Used for programmatic recovery of system database and RBAC.
 * This is a temporary tool to bypass terminal environment issues.
 */
class SystemRecoveryController extends Controller
{
    public function sync()
    {
        $report = [];

        try {
            // 1. Run Migrations
            Artisan::call('migrate', ['--force' => true]);
            $report[] = "SUCCESS: Migrations executed. " . Artisan::output();

            // 2. Fix employee_kpi_records (Add missing columns)
            if (Schema::hasTable('employee_kpi_records')) {
                Schema::table('employee_kpi_records', function ($table) {
                    if (!Schema::hasColumn('employee_kpi_records', 'submission_status')) {
                        $table->enum('submission_status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft')->after('status');
                    }
                    if (!Schema::hasColumn('employee_kpi_records', 'submitted_at')) {
                        $table->timestamp('submitted_at')->nullable()->after('submission_status');
                    }
                    if (!Schema::hasColumn('employee_kpi_records', 'reviewer_notes')) {
                        $table->text('reviewer_notes')->nullable()->after('submitted_at');
                    }
                });
                $report[] = "SUCCESS: Ensured 'submission_status', 'submitted_at', and 'reviewer_notes' columns in 'employee_kpi_records'.";
            }

            // 3. Sync Roles
            $masterAdminRole = Role::firstOrCreate(
                ['title' => Roles::MASTER_ADMIN],
                ['description' => 'Full System Access Administrator', 'access' => Roles::all()]
            );
            $report[] = "SUCCESS: Role '" . Roles::MASTER_ADMIN . "' ensured.";

            // 4. Fix Admin User
            $adminUser = User::where('email', 'admin@aratech.id')->first();
            if ($adminUser) {
                $employee = $adminUser->employee;
                if ($employee) {
                    $employee->update([
                        'role_id' => $masterAdminRole->id,
                        'status' => 'active'
                    ]);
                    $report[] = "SUCCESS: 'admin@aratech.id' linked to '" . Roles::MASTER_ADMIN . "'.";
                } else {
                    // Create employee record if missing
                    $hrDept = Department::firstOrCreate(['name' => 'Human Resources']);
                    $employee = Employee::create([
                        'user_id' => $adminUser->id,
                        'nik' => 'ADMIN-001',
                        'fullname' => 'System Administrator',
                        'email' => 'admin@aratech.id',
                        'department_id' => $hrDept->id,
                        'role_id' => $masterAdminRole->id,
                        'hire_date' => now(),
                        'status' => 'active',
                        'employee_status' => 'permanent'
                    ]);
                    $adminUser->update(['employee_id' => $employee->id]);
                    $report[] = "SUCCESS: Created employee record for 'admin@aratech.id' and linked to '" . Roles::MASTER_ADMIN . "'.";
                }
            } else {
                $report[] = "ERROR: User 'admin@aratech.id' not found. Please run the create_admin.php script first.";
            }

            // 5. Clear Caches
            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            $report[] = "SUCCESS: View, Config, and Cache cleared.";

            return response()->json([
                'status' => 'finished',
                'timestamp' => now()->toDateTimeString(),
                'steps' => $report
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
