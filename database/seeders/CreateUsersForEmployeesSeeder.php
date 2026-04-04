<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CreateUsersForEmployeesSeeder extends Seeder
{
    public function run(): void
    {
        $employees = DB::table('employees')->get();
        $created = [];

        foreach ($employees as $employee) {
            // Check if a user exists for this employee (by employee_id or by email)
            $user = DB::table('users')
                ->where('employee_id', (string) $employee->id)
                ->orWhere('email', $employee->email)
                ->first();

            if (! $user) {
                $password = Str::random(12);

                DB::table('users')->insert([
                    'name' => $employee->fullname,
                    'email' => $employee->email,
                    'password' => Hash::make($password),
                    'employee_id' => (string) $employee->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $created[] = [
                    'employee_id' => $employee->id,
                    'email' => $employee->email,
                    'password' => $password,
                ];
            }
        }

        if (count($created) === 0) {
            $this->command->info('No new users were created. All employees already have user accounts.');
            return;
        }

        $this->command->info('Created user accounts for employees:');
        foreach ($created as $c) {
            $this->command->info("- employee_id={$c['employee_id']} email={$c['email']} password={$c['password']}");
        }
    }
}
