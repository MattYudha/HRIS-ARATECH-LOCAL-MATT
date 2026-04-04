<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Foundation;
use App\Models\EducationLevel;
use App\Models\Position;
use App\Models\IdentityType;
use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\BankAccount;
use App\Models\DocumentIdentity;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Foundations
        $foundations = [
            ['foundation_name' => 'Aratech Foundation', 'email' => 'foundation@aratech.id', 'phone' => '0211234567', 'address' => 'Jakarta, Indonesia', 'status' => 'active'],
            ['foundation_name' => 'Tech Care Foundation', 'email' => 'care@tech.id', 'phone' => '0217654321', 'address' => 'Bandung, Indonesia', 'status' => 'active'],
        ];

        foreach ($foundations as $f) {
            Foundation::updateOrCreate(['foundation_name' => $f['foundation_name']], $f);
        }

        // 2. Education Levels
        $educationLevels = [
            'SMA/SMK',
            'D3',
            'S1',
            'S2',
            'S3',
        ];

        foreach ($educationLevels as $el) {
            EducationLevel::updateOrCreate(['level' => $el]);
        }

        // 3. Identity Types
        $identityTypes = [
            'KTP',
            'SIM',
            'Passport',
        ];

        foreach ($identityTypes as $it) {
            IdentityType::updateOrCreate(['name' => $it]);
        }

        // 4. Positions
        $positions = [
            ['position_name' => 'Senior Developer', 'title' => 'Sr. Dev', 'level' => 'Senior', 'salary_grade' => 'A1', 'description' => 'Senior Software Developer'],
            ['position_name' => 'Project Manager', 'title' => 'PM', 'level' => 'Manager', 'salary_grade' => 'B2', 'description' => 'Oversees project delivery'],
            ['position_name' => 'Quality Assurance', 'title' => 'QA', 'level' => 'Staff', 'salary_grade' => 'C3', 'description' => 'Ensures software quality'],
        ];

        foreach ($positions as $p) {
            Position::updateOrCreate(['position_name' => $p['position_name']], $p);
        }

        // 5. Employees with new fields
        $deptId = Department::first()->id ?? 1;
        $roleId = Role::where('title', 'Developer')->first()->id ?? 4;
        $foundationId = Foundation::first()->foundation_id;
        $eduId = EducationLevel::where('level', 'S1')->first()->education_level_id;

        $employees = [
            [
                'emp_code' => 'EMP-00100',
                'nik' => '3201234567890001',
                'fullname' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'phone_number' => '081234567890',
                'npwp' => '12.345.678.9-012.000',
                'place_of_birth' => 'Jakarta',
                'birth_date' => '1990-05-15',
                'gender' => 'Male',
                'religion' => 'Islam',
                'marital_status' => 'Married',
                'address' => 'Jl. Merdeka No. 10, Jakarta',
                'hire_date' => '2023-01-10',
                'department_id' => $deptId,
                'role_id' => $roleId,
                'status' => 'active',
                'employee_status' => 'Permanent',
                'salary' => 8500000,
                'foundation_id' => $foundationId,
                'education_level_id' => $eduId,
            ],
            [
                'emp_code' => 'EMP-00101',
                'nik' => '3201234567890002',
                'fullname' => 'Siti Aminah',
                'email' => 'siti.aminah@example.com',
                'phone_number' => '081234567891',
                'npwp' => '12.345.678.9-012.001',
                'place_of_birth' => 'Bandung',
                'birth_date' => '1992-08-20',
                'gender' => 'Female',
                'religion' => 'Islam',
                'marital_status' => 'Single',
                'address' => 'Jl. Dago No. 5, Bandung',
                'hire_date' => '2023-03-15',
                'department_id' => $deptId,
                'role_id' => $roleId,
                'status' => 'active',
                'employee_status' => 'Contract',
                'salary' => 7000000,
                'foundation_id' => $foundationId,
                'education_level_id' => $eduId,
            ],
        ];

        foreach ($employees as $empData) {
            $employee = Employee::updateOrCreate(['email' => $empData['email']], $empData);

            // Create User account
            User::updateOrCreate(['email' => $employee->email], [
                'name' => $employee->fullname,
                'password' => Hash::make('password123'),
                'employee_id' => (string) $employee->id,
            ]);

            // 6. Bank Accounts
            BankAccount::updateOrCreate(['employee_id' => $employee->id], [
                'bank_name' => 'BCA',
                'account_no' => '123456789' . $employee->id,
                'account_holder' => $employee->fullname,
                'is_primary' => true,
            ]);

            // 7. Identity Documents
            DocumentIdentity::updateOrCreate(['employee_id' => $employee->id], [
                'identity_type_id' => IdentityType::first()->identity_type_id,
                'identity_number' => $employee->nik,
                'file_name' => 'ktp_' . $employee->id . '.pdf',
                'description' => 'KTP of ' . $employee->fullname,
            ]);

            // 8. Employee Positions
            EmployeePosition::updateOrCreate(['employee_id' => $employee->id, 'start_date' => $employee->hire_date], [
                'position_id' => Position::first()->position_id,
                'department_id' => $deptId,
                'sk_number' => 'SK/2023/' . Str::random(5),
                'base_on_salary' => $employee->salary,
                'is_supervisor' => false,
                'is_active' => true,
                'status' => 'active',
            ]);
        }
    }
}
