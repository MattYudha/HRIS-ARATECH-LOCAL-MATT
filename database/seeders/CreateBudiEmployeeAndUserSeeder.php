<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateBudiEmployeeAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan department IT (id=2) dan role Developer (id=2) sudah ada
        // sesuai data di hrapps.sql.

        $employee = Employee::firstOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'fullname'      => 'Budi Santoso',
                'phone_number'  => '+62 812-1111-2222',
                'address'       => 'Jl. Mawar No. 10, Jakarta',
                'birth_date'    => '1995-05-10 00:00:00',
                'hire_date'     => '2025-11-25 00:00:00',
                'department_id' => 2, // IT
                'role_id'       => 2, // Developer
                'supervisor_id' => 1, // misal employee id 1 sebagai atasan
                'status'        => 'active',
                'salary'        => 8000000,
            ]
        );

        User::firstOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'name'        => 'Budi Santoso',
                // password asli untuk login: Budi123!
                'password'    => Hash::make('Budi123!'),
                'employee_id' => $employee->id,
            ]
        );
    }
}
