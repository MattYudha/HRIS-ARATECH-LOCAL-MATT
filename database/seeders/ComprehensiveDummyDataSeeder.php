<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\Models\Presence;
use App\Models\Task;
use App\Models\Inventory;
use App\Models\InventoryUsageLog;
use App\Models\Letter;
use App\Models\Signature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class ComprehensiveDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create additional departments
        $finance = Department::create([
            'name' => 'Finance',
            'description' => 'Financial Management',
            'status' => 'active',
        ]);

        $marketing = Department::create([
            'name' => 'Marketing',
            'description' => 'Marketing and Communications',
            'status' => 'active',
        ]);

        // Get existing departments and roles
        $hr = Department::where('name', 'HR')->first();
        $it = Department::where('name', 'IT')->first();
        $sales = Department::where('name', 'Sales')->first();

        $manager = Role::where('title', 'Manager')->first();
        $developer = Role::where('title', 'Developer')->first();
        $salesperson = Role::where('title', 'Salesperson')->first();
        $hrRole = Role::where('title', 'HR')->first();

        // Create Finance Manager role
        $financeManager = Role::create([
            'title' => 'Finance Manager',
            'description' => 'Handles financial operations',
        ]);

        // Create more employees
        $employees = [
            [
                'fullname' => 'Sarah Johnson',
                'email' => 'sarah.johnson@aratech.com',
                'phone_number' => '081234567890',
                'address' => 'Jakarta Selatan',
                'birth_date' => '1990-05-15',
                'hire_date' => '2023-01-15',
                'department_id' => $hr->id,
                'role_id' => $hrRole->id,
                'supervisor_id' => null,
                'status' => 'active',
                'salary' => 8000000,
            ],
            [
                'fullname' => 'Michael Chen',
                'email' => 'michael.chen@aratech.com',
                'phone_number' => '081234567891',
                'address' => 'Jakarta Pusat',
                'birth_date' => '1988-08-20',
                'hire_date' => '2022-06-01',
                'department_id' => $it->id,
                'role_id' => $developer->id,
                'supervisor_id' => 1,
                'status' => 'active',
                'salary' => 12000000,
            ],
            [
                'fullname' => 'Lisa Anderson',
                'email' => 'lisa.anderson@aratech.com',
                'phone_number' => '081234567892',
                'address' => 'Jakarta Barat',
                'birth_date' => '1992-03-10',
                'hire_date' => '2023-03-15',
                'department_id' => $sales->id,
                'role_id' => $salesperson->id,
                'supervisor_id' => 1,
                'status' => 'active',
                'salary' => 7000000,
            ],
            [
                'fullname' => 'David Kumar',
                'email' => 'david.kumar@aratech.com',
                'phone_number' => '081234567893',
                'address' => 'Jakarta Timur',
                'birth_date' => '1991-11-25',
                'hire_date' => '2022-09-01',
                'department_id' => $finance->id,
                'role_id' => $financeManager->id,
                'supervisor_id' => null,
                'status' => 'active',
                'salary' => 15000000,
            ],
            [
                'fullname' => 'Maria Garcia',
                'email' => 'maria.garcia@aratech.com',
                'phone_number' => '081234567894',
                'address' => 'Tangerang',
                'birth_date' => '1993-07-08',
                'hire_date' => '2023-05-20',
                'department_id' => $marketing->id,
                'role_id' => $manager->id,
                'supervisor_id' => null,
                'status' => 'active',
                'salary' => 9000000,
            ],
        ];

        foreach ($employees as $empData) {
            $employee = Employee::create($empData);
            
            // Create user account for each employee
            User::create([
                'name' => $empData['fullname'],
                'email' => $empData['email'],
                'password' => Hash::make('password123'),
                'employee_id' => $employee->id,
            ]);
        }

        // Create presences for last 30 days
        $allEmployees = Employee::all();
        foreach ($allEmployees as $employee) {
            for ($i = 1; $i <= 30; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                Presence::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $date->copy()->setTime(8, rand(0, 30), 0),
                    'check_out' => $date->copy()->setTime(17, rand(0, 30), 0),
                    'status' => 'present',
                ]);
            }
        }

        // Create tasks
        $taskData = [
            ['title' => 'Implement Authentication System', 'assigned_to' => 2, 'due_date' => Carbon::now()->addDays(7), 'status' => 'in-progress'],
            ['title' => 'Design Marketing Campaign', 'assigned_to' => 7, 'due_date' => Carbon::now()->addDays(5), 'status' => 'pending'],
            ['title' => 'Financial Report Q4', 'assigned_to' => 6, 'due_date' => Carbon::now()->addDays(3), 'status' => 'in-progress'],
            ['title' => 'Client Meeting Preparation', 'assigned_to' => 5, 'due_date' => Carbon::now()->addDays(2), 'status' => 'pending'],
            ['title' => 'Code Review Backend API', 'assigned_to' => 4, 'due_date' => Carbon::now()->addDays(1), 'status' => 'in-progress'],
            ['title' => 'Employee Onboarding Process', 'assigned_to' => 3, 'due_date' => Carbon::now()->addDays(10), 'status' => 'completed'],
            ['title' => 'Database Optimization', 'assigned_to' => 2, 'due_date' => Carbon::now()->addDays(14), 'status' => 'pending'],
        ];

        foreach ($taskData as $task) {
            Task::create([
                'title' => $task['title'],
                'description' => 'Task description for: ' . $task['title'],
                'assigned_to' => $task['assigned_to'],
                'due_date' => $task['due_date'],
                'status' => $task['status'],
            ]);
        }

        // Create inventory usage logs
        $inventories = Inventory::all();
        foreach ($inventories as $inventory) {
            // Create 3-5 usage logs per item
            $logCount = rand(3, 5);
            for ($i = 0; $i < $logCount; $i++) {
                $borrowedDate = Carbon::now()->subDays(rand(10, 60));
                $returnedDate = $borrowedDate->copy()->addDays(rand(2, 7));
                
                InventoryUsageLog::create([
                    'inventory_id' => $inventory->id,
                    'employee_id' => $allEmployees->random()->id,
                    'borrowed_date' => $borrowedDate,
                    'returned_date' => $returnedDate,
                    'notes' => 'Borrowed for daily operations',
                ]);
            }
        }

        // Create more letters with different statuses
        $letterData = [
            [
                'user_id' => 3,
                'subject' => 'Surat Keterangan Kerja',
                'content' => '<p>Dengan ini menerangkan bahwa karyawan yang bersangkutan bekerja di PT Aratech Indonesia.</p>',
                'letter_type' => 'official',
                'status' => 'approved',
                'letter_number' => '001/HR/12/2025',
                'approved_date' => Carbon::now()->subDays(5),
                'approver_id' => 5,
            ],
            [
                'user_id' => 4,
                'subject' => 'Surat Tugas Perjalanan Dinas',
                'content' => '<p>Menugaskan untuk melakukan perjalanan dinas ke Surabaya.</p>',
                'letter_type' => 'official',
                'status' => 'pending',
                'letter_number' => '002/HR/12/2025',
            ],
            [
                'user_id' => 5,
                'subject' => 'Memo Rapat Internal',
                'content' => '<p>Mengundang seluruh staff untuk menghadiri rapat bulanan.</p>',
                'letter_type' => 'memo',
                'status' => 'approved',
                'letter_number' => '003/HR/12/2025',
                'approved_date' => Carbon::now()->subDays(2),
                'approver_id' => 5,
            ],
            [
                'user_id' => 6,
                'subject' => 'Pengumuman Libur Nasional',
                'content' => '<p>Pemberitahuan mengenai jadwal libur nasional bulan depan.</p>',
                'letter_type' => 'notice',
                'status' => 'printed',
                'letter_number' => '004/HR/12/2025',
                'approved_date' => Carbon::now()->subDays(10),
                'printed_date' => Carbon::now()->subDays(8),
                'approver_id' => 5,
            ],
        ];

        foreach ($letterData as $letter) {
            Letter::create(array_merge($letter, [
                'created_date' => Carbon::now()->subDays(rand(10, 30)),
            ]));
        }

        // Create digital signatures for approved letters
        $approvedLetters = Letter::whereIn('status', ['approved', 'printed'])->get();
        foreach ($approvedLetters as $letter) {
            // Create signature from letter creator
            $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
            
            $signature = Signature::create([
                'user_id' => $letter->user_id,
                'signable_id' => $letter->id,
                'signable_type' => 'App\Models\Letter',
                'signature_image' => $signatureData,
                'signature_hash' => hash('sha256', $signatureData . $letter->user_id . $letter->id . now()),
                'signed_date' => $letter->approved_date ?? now(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0',
                'is_verified' => true,
                'verified_at' => $letter->approved_date ?? now(),
                'verification_token' => \Illuminate\Support\Str::random(64),
            ]);

            // Create verification record
            $signature->verifications()->create([
                'verified_by_id' => $letter->approver_id ?? 5,
                'status' => 'verified',
                'remarks' => 'Signature verified by HR',
                'verification_date' => $letter->approved_date ?? now(),
            ]);
        }

        $this->command->info('✓ Created ' . count($employees) . ' new employees');
        $this->command->info('✓ Created presence records for all employees (30 days)');
        $this->command->info('✓ Created ' . count($taskData) . ' tasks');
        $this->command->info('✓ Created inventory usage logs');
        $this->command->info('✓ Created ' . count($letterData) . ' letters');
        $this->command->info('✓ Created digital signatures for approved letters');
    }
}
