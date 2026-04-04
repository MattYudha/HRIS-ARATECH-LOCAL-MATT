<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\BankAccount;
use App\Models\DocumentIdentity;
use App\Models\IdentityType;
use App\Models\EmployeeMutation;
use App\Models\Department;
use App\Models\Role;
use App\Models\EmployeePosition;
use App\Models\Position;
use App\Models\EducationLevel;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProfileDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure common Identity Types exist
        $types = ['KTP', 'SIM', 'Passport', 'NPWP', 'BPJS Kesehatan', 'BPJS Ketenagakerjaan'];
        foreach ($types as $typeName) {
            IdentityType::firstOrCreate(['name' => $typeName]);
        }

        $allEmployees = Employee::all();
        $banks = ['BCA', 'Mandiri', 'BNI', 'BRI', 'CIMB Niaga'];
        $departments = Department::all();
        $roles = Role::all();
        $positions = Position::all();
        $educationLevels = EducationLevel::all();

        // Ensure at least one position exists
        if ($positions->isEmpty()) {
            $positions = collect([Position::create(['title' => 'Staff', 'position_name' => 'Staff'])]);
        }

        foreach ($allEmployees as $employee) {
            // 1.5 Assign random education level if null
            if (is_null($employee->education_level_id) && $educationLevels->isNotEmpty()) {
                $employee->update([
                    'education_level_id' => $educationLevels->random()->education_level_id
                ]);
            }

            // 2. Create Bank Accounts if not exists
            if ($employee->bankAccounts()->count() === 0) {
                // Primary Account
                BankAccount::create([
                    'employee_id' => $employee->id,
                    'bank_name' => $banks[array_rand($banks)],
                    'account_no' => (string)rand(1000000000, 9999999999),
                    'account_holder' => strtoupper($employee->fullname),
                    'is_primary' => true,
                ]);
            }

            // 3. Create Documents if not exists
            if ($employee->documentIdentities()->count() === 0) {
                // KTP
                $ktpType = IdentityType::where('name', 'KTP')->first();
                if ($ktpType) {
                    DocumentIdentity::create([
                        'employee_id' => $employee->id,
                        'identity_type_id' => $ktpType->identity_type_id,
                        'identity_number' => (string)rand(3100000000000000, 3199999999999999),
                        'description' => 'Kartu Tanda Penduduk',
                    ]);
                }
            }

            // 4. Create Career History (Employee Mutations) if not exists
            if ($employee->mutations()->count() === 0) {
                $hireDate = $employee->hire_date ?? Carbon::now()->subYears(2);
                
                EmployeeMutation::create([
                    'employee_id' => $employee->id,
                    'old_department_id' => $departments->random()->id,
                    'new_department_id' => $employee->department_id,
                    'old_role_id' => $roles->random()->id,
                    'new_role_id' => $employee->role_id,
                    'old_salary' => $employee->salary * 0.8,
                    'new_salary' => $employee->salary,
                    'mutation_date' => $hireDate->copy()->addMonths(6),
                    'type' => 'promotion',
                    'reason' => 'Promotion for good performance.',
                    'created_by' => 1,
                ]);
            }

            // 5. Create Employee Positions if not exists
            if ($employee->employeePositions()->count() === 0) {
                EmployeePosition::create([
                    'employee_id' => $employee->id,
                    'position_id' => $positions->random()->position_id,
                    'department_id' => $employee->department_id,
                    'start_date' => $employee->hire_date ?? Carbon::now()->subYears(1),
                    'is_active' => true,
                    'pay_grade_id' => rand(10, 15), // JG/PG dummy
                ]);
            }
        }
    }
}
