<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateHrCredentialsSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure HR role exists
        $hrRoleId = DB::table('roles')->where('title', 'HR')->value('id');

        if (! $hrRoleId) {
            $hrRoleId = DB::table('roles')->insertGetId([
                'title' => 'HR',
                'description' => 'Human Resources',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // Find an HR department id (fallback to first department if none)
        $hrDeptId = DB::table('departments')->where('name', 'HR')->value('id') ?? DB::table('departments')->value('id');

        // Create employee if not exists
        $employeeEmail = 'hr@example.com';
        $employeeId = DB::table('employees')->where('email', $employeeEmail)->value('id');

        if (! $employeeId) {
            $employeeId = DB::table('employees')->insertGetId([
                'fullname' => 'HR Admin',
                'email' => $employeeEmail,
                'phone_number' => '+62 812-3456-7890',
                'address' => 'Office - Human Resources',
                'birth_date' => null,
                'hire_date' => Carbon::now(),
                'department_id' => $hrDeptId,
                'role_id' => $hrRoleId,
                'supervisor_id' => null,
                'status' => 'active',
                'salary' => 0.00,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);
        }

        // Create user for that employee if not exists
        $userEmail = 'hr@example.com';
        $existingUser = DB::table('users')->where('email', $userEmail)->first();

        if (! $existingUser) {
            DB::table('users')->insert([
                'name' => 'HR Admin',
                'email' => $userEmail,
                'password' => Hash::make('Password123!'),
                'employee_id' => (string) $employeeId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            // Ensure employee_id is set on existing user
            if (empty($existingUser->employee_id)) {
                DB::table('users')->where('id', $existingUser->id)->update(['employee_id' => (string) $employeeId]);
            }
        }

        $this->command->info('HR credentials created: email=hr@example.com password=Password123!');
    }
}
