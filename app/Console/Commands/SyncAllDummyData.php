<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Department;
use App\Models\Task;
use App\Models\LeaveRequest;
use App\Models\Letter;
use App\Models\Inventory;
use App\Models\InventoryCategory;
use App\Models\KPI;
use App\Models\LetterTemplate;
use App\Models\LetterConfiguration;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncAllDummyData extends Command
{
    protected $signature = 'sync:dummy-data';
    protected $description = 'Synchronize and create all dummy data (roles, departments, employees, tasks, leave, letters, inventory, KPIs)';

    public function handle()
    {
        $this->info('Starting comprehensive dummy data sync...');

        try {
            // 1. Create Professional Roles
            $this->info('Creating professional roles...');
            $superAdminRole = Role::firstOrCreate(
                ['title' => 'Super Admin'],
                ['description' => 'Full access to all system features including Backup and Audit Trail']
            );
            $hrRole = Role::firstOrCreate(
                ['title' => 'HR Administrator'],
                ['description' => 'Human Resources Administrator']
            );
            $managerRole = Role::firstOrCreate(
                ['title' => 'Manager / Unit Head'],
                ['description' => 'Department Manager / Unit Head']
            );
            $supervisorRole = Role::firstOrCreate(
                ['title' => 'Supervisor'],
                ['description' => 'Supervisor / Team Leader (Layer 1 Approval)']
            );
            $employeeRole = Role::firstOrCreate(
                ['title' => 'Employee'],
                ['description' => 'Standard Employee with Self Service (ESS) access']
            );
            $financeRole = Role::firstOrCreate(
                ['title' => 'Finance'],
                ['description' => 'Finance Operator and Viewer']
            );
            $this->info('✓ Professional roles synced');

            // 2. Create Departments
            $this->info('Creating departments...');
            $deptIT = Department::firstOrCreate(
                ['name' => 'IT'],
                ['description' => 'Information Technology', 'status' => 'active']
            );
            $deptHR = Department::firstOrCreate(
                ['name' => 'Human Resources'],
                ['description' => 'Human Resources Department', 'status' => 'active']
            );
            $deptMarketing = Department::firstOrCreate(
                ['name' => 'Marketing'],
                ['description' => 'Marketing Department', 'status' => 'active']
            );
            $deptEmployee = Department::firstOrCreate(
                ['name' => 'Employee'],
                ['description' => 'Employee Department', 'status' => 'active']
            );
            $deptOps = Department::firstOrCreate(
                ['name' => 'Operations'],
                ['description' => 'Operations Department', 'status' => 'active']
            );
            $this->info('✓ Departments synced');

            // 3. Create Users and Employees
            $this->info('Creating employees and users...');
            
            // Admin user
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Administrator',
                    'password' => Hash::make('password'),
                ]
            );
            $adminEmp = Employee::withTrashed()->firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'user_id' => $adminUser->id,
                    'fullname' => 'Administrator',
                    'email' => 'admin@example.com',
                    'phone_number' => '081234567890',
                    'address' => '123 Admin Street',
                    'department_id' => $deptHR->id,
                    'role_id' => $hrRole->id,
                    'supervisor_id' => null,
                    'hire_date' => '2020-01-01',
                    'status' => 'active',
                    'employee_status' => 'permanent',
                    'npwp' => '00.123.456.7-001.000',
                    'salary' => 10000000,
                ]
            );
            if ($adminEmp->trashed()) $adminEmp->restore();
            $adminUser->update(['employee_id' => $adminEmp->id]);

            // Manager / Unit Head
            $mgrUser = User::firstOrCreate(
                ['email' => 'manager@example.com'],
                [
                    'name' => 'John Manager / Unit Head',
                    'password' => Hash::make('password'),
                ]
            );
            $mgrEmp = Employee::withTrashed()->firstOrCreate(
                ['email' => 'manager@example.com'],
                [
                    'user_id' => $mgrUser->id,
                    'fullname' => 'John Manager / Unit Head',
                    'email' => 'manager@example.com',
                    'phone_number' => '081234567891',
                    'address' => '456 Manager / Unit Head Ave',
                    'department_id' => $deptIT->id,
                    'role_id' => $managerRole->id,
                    'supervisor_id' => $adminEmp->id,
                    'hire_date' => '2021-01-01',
                    'status' => 'active',
                    'employee_status' => 'permanent',
                    'npwp' => '00.123.456.7-002.000',
                    'salary' => 8000000,
                ]
            );
            if ($mgrEmp->trashed()) $mgrEmp->restore();
            $mgrUser->update(['employee_id' => $mgrEmp->id]);

            // Employees
            $employees = [];
            for ($i = 3; $i <= 5; $i++) {
                $dept = $i === 3 ? $deptIT : ($i === 4 ? $deptMarketing : $deptEmployee);
                // Assign Employee 3 to Supervisor, Employee 4 to Super Admin (for testing), Employee 5 to Employee
                $roleId = $i === 3 ? $supervisorRole->id : ($i === 4 ? $superAdminRole->id : $employeeRole->id);
                
                $emp = Employee::withTrashed()->firstOrCreate(
                    ['email' => "employee{$i}@example.com"],
                    [
                        'user_id' => User::firstOrCreate(
                            ['email' => "employee{$i}@example.com"],
                            [
                                'name' => "Employee {$i}",
                                'password' => Hash::make('password'),
                            ]
                        )->id,
                        'fullname' => "Employee {$i}",
                        'email' => "employee{$i}@example.com",
                        'phone_number' => "08123456789{$i}",
                        'address' => "Street {$i}",
                        'department_id' => $dept->id,
                        'role_id' => $roleId,
                        'supervisor_id' => $mgrEmp->id,
                        'hire_date' => '2022-01-01',
                        'status' => 'active',
                        'employee_status' => $i === 5 ? 'contract' : 'permanent',
                        'npwp' => '00.123.456.7-' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.000',
                        'salary' => 6000000,
                    ]
                );

                if ($emp->trashed()) {
                    $emp->restore();
                }
                
                User::where('email', "employee{$i}@example.com")->update(['employee_id' => $emp->id]);
                $employees[] = $emp;
            }
            $this->info('✓ Employees synced (5 total)');

            // 4. Create KPIs
            $this->info('Creating KPIs...');
            $kpis = [
                ['code' => 'KPI001', 'name' => 'Attendance Rate', 'category' => 'Attendance', 'unit' => '%', 'target_value' => 95, 'weight' => 20, 'metric_category' => 'attendance', 'metric_key' => 'attendance_rate'],
                ['code' => 'KPI003', 'name' => 'Tasks On-Time', 'category' => 'Productivity', 'unit' => '%', 'target_value' => 90, 'weight' => 25, 'metric_category' => 'productivity', 'metric_key' => 'on_time_delivery_rate'],
                ['code' => 'KPI002', 'name' => 'Task Completion Rate', 'category' => 'Productivity', 'unit' => '%', 'target_value' => 95, 'weight' => 20, 'metric_category' => 'productivity', 'metric_key' => 'task_completion_rate'],
                ['code' => 'KPI004', 'name' => 'Code Quality Score', 'category' => 'Quality', 'unit' => '%', 'target_value' => 85, 'weight' => 15, 'metric_category' => 'quality', 'metric_key' => 'task_quality_score'],
                ['code' => 'KPI006', 'name' => 'Policy Compliance', 'category' => 'Department', 'unit' => '%', 'target_value' => 100, 'weight' => 10, 'metric_category' => 'behavior', 'metric_key' => 'compliance_score'],
                ['code' => 'KPI007', 'name' => 'Conduct Score', 'category' => 'Behavior', 'unit' => '%', 'target_value' => 100, 'weight' => 10, 'metric_category' => 'behavior', 'metric_key' => 'conduct_score'],
            ];

            foreach ($kpis as $kpi) {
                KPI::updateOrCreate(
                    ['code' => $kpi['code']],
                    $kpi
                );
            }
            $this->info('✓ KPIs synced (10 total)');

            // 4a. Map KPIs to Roles
            $allRoles = Role::all();
            $allKPIs = KPI::all();
            foreach ($allRoles as $role) {
                foreach ($allKPIs as $kpi) {
                    \DB::table('role_kpi')->updateOrInsert(
                        ['role_id' => $role->id, 'kpi_id' => $kpi->id],
                        ['target_value' => $kpi->target_value, 'weight' => $kpi->weight, 'updated_at' => now()]
                    );
                }
            }
            $this->info('✓ Role-KPI mappings synced');

            // 5. Create Tasks
            $this->info('Creating tasks...');
            Task::firstOrCreate(
                ['id' => 1],
                [
                    'employee_id' => $employees[0]->id,
                    'title' => 'Complete API Documentation',
                    'description' => 'Write comprehensive API documentation',
                    'status' => 'pending',
                    'priority' => 'high',
                    'due_date' => Carbon::now()->addDays(7),
                ]
            );
            Task::firstOrCreate(
                ['id' => 2],
                [
                    'employee_id' => $employees[1]->id,
                    'title' => 'Marketing Campaign Design',
                    'description' => 'Design new marketing campaign',
                    'status' => 'completed',
                    'priority' => 'medium',
                    'due_date' => Carbon::now()->subDays(5),
                ]
            );
            $this->info('✓ Tasks synced (2 total)');

            // 6. Create Leave Requests
            $this->info('Creating leave requests...');
            LeaveRequest::firstOrCreate(
                ['id' => 1],
                [
                    'employee_id' => $employees[0]->id,
                    'leave_type' => 'annual',
                    'start_date' => Carbon::now()->addDays(14),
                    'end_date' => Carbon::now()->addDays(16),
                    'status' => 'pending',
                ]
            );
            LeaveRequest::firstOrCreate(
                ['id' => 2],
                [
                    'employee_id' => $employees[1]->id,
                    'leave_type' => 'sick',
                    'start_date' => Carbon::now()->subDays(2),
                    'end_date' => Carbon::now()->subDays(1),
                    'status' => 'approved',
                ]
            );
            $this->info('✓ Leave Requests synced (2 total)');

            // 7. Create Inventory Categories
            $this->info('Creating inventory categories...');
            $categories = [
                ['name' => 'Office Supplies', 'description' => 'Pens, papers, etc'],
                ['name' => 'Electronics', 'description' => 'Monitors, keyboards, etc'],
                ['name' => 'Furniture', 'description' => 'Desks, chairs, etc'],
                ['name' => 'Software Licenses', 'description' => 'License keys and software'],
                ['name' => 'Network Equipment', 'description' => 'Routers, switches, etc'],
            ];
            foreach ($categories as $i => $cat) {
                InventoryCategory::firstOrCreate(
                    ['id' => $i + 1],
                    $cat
                );
            }
            $this->info('✓ Inventory Categories synced (5 total)');

            // 8. Create Inventory Items
            $this->info('Creating inventory items...');
            $inventories = [
                ['id' => 1, 'inventory_category_id' => 1, 'name' => 'Ballpoint Pens', 'quantity' => 100],
                ['id' => 2, 'inventory_category_id' => 2, 'name' => 'LCD Monitor 24\"', 'quantity' => 15],
                ['id' => 3, 'inventory_category_id' => 3, 'name' => 'Office Chair', 'quantity' => 20],
                ['id' => 4, 'inventory_category_id' => 4, 'name' => 'MS Office License', 'quantity' => 50],
                ['id' => 5, 'inventory_category_id' => 5, 'name' => 'Cisco Switch', 'quantity' => 3],
            ];
            foreach ($inventories as $inv) {
                Inventory::firstOrCreate(
                    ['id' => $inv['id']],
                    $inv
                );
            }
            $this->info('✓ Inventory Items synced (5 total)');

            // 8a. Create Letter Templates
            $this->info('Creating letter templates...');
            $templates = [
                [
                    'name' => 'Surat Penawaran Kerja',
                    'slug' => 'job-offer',
                    'description' => 'Template surat penawaran pekerjaan untuk karyawan baru',
                    'content' => "[COMPANY_NAME]\n\nYang Terhormat [EMPLOYEE_NAME],\n\nDengan senang hati kami menawarkan posisi [POSITION] kepada Anda. Silakan hubungi HR Administrator untuk detail lengkap.\n\nHormat kami,\nHR Administrator Department"
                ],
                [
                    'name' => 'Surat Kontrak Kerja',
                    'slug' => 'employment-contract',
                    'description' => 'Template kontrak kerja permanent',
                    'content' => "KONTRAK KERJA\n\nDengan ini disepakati antara [COMPANY_NAME] dan [EMPLOYEE_NAME] untuk mengadakan perjanjian kerja sebagai berikut:\n\nPos: [POSITION]\nGaji: [SALARY]\nJakarta, [DATE]\n\nTanda Tangan:"
                ],
                [
                    'name' => 'Surat Rekomendasi',
                    'slug' => 'recommendation-letter',
                    'description' => 'Template surat rekomendasi kerja',
                    'content' => "SURAT REKOMENDASI\n\nDengan ini saya merekomendasikan [EMPLOYEE_NAME] sebagai karyawan yang kompeten dan profesional. [EMPLOYEE_NAME] telah bekerja dengan baik selama [PERIOD].\n\nHormat kami,\n[RECOMMENDER_NAME]"
                ],
                [
                    'name' => 'Surat Pernyataan Kerja',
                    'slug' => 'work-certificate',
                    'description' => 'Template sertifikat kerja',
                    'content' => "SURAT KETERANGAN KERJA\n\nYang bertanda tangan di bawah ini menerangkan bahwa [EMPLOYEE_NAME] bekerja di [COMPANY_NAME] sejak [START_DATE] sampai [END_DATE] sebagai [POSITION].\n\nJakarta, [DATE]\nDiminta untuk keperluan: [PURPOSE]"
                ],
                [
                    'name' => 'Surat Izin Cuti',
                    'slug' => 'leave-permission',
                    'description' => 'Template surat izin cuti',
                    'content' => "SURAT IZIN CUTI\n\nDengan ini saya mengajukan izin cuti sebanyak [DAYS] hari mulai dari [START_DATE] sampai [END_DATE].\n\nAlasan: [REASON]\n\nHormat kami,\n[EMPLOYEE_NAME]"
                ]
            ];
            foreach ($templates as $i => $template) {
                LetterTemplate::firstOrCreate(
                    ['name' => $template['name']],
                    $template
                );
            }
            $this->info('✓ Letter Templates synced (5 total)');

            // 8b. Create Letter Configuration
            $this->info('Creating letter configuration...');
            LetterConfiguration::firstOrCreate(
                ['id' => 1],
                [
                    'letter_number_format' => '{NUMBER}/{DEPT}/{MONTH}/{YEAR}',
                    'current_number' => 0,
                    'company_name' => 'PT Aratech Indonesia',
                    'company_address' => 'Jl. Gatot Subroto No. 1, Jakarta',
                    'company_phone' => '(021) 1234-5678',
                    'company_email' => 'info@aratech.co.id',
                ]
            );
            $this->info('✓ Letter Configuration synced');

            // 9. Seed KPI Records
            $this->info('Creating KPI records...');
            $kpiModels = KPI::all();
            $period = '2025-12';
            $inserted = 0;

            foreach ($employees as $emp) {
                foreach ($kpiModels as $kpi) {
                    // Check if already exists
                    $exists = DB::selectOne(
                        'SELECT id FROM employee_kpi_records WHERE employee_id = ? AND kpi_id = ? AND period = ?',
                        [$emp->id, $kpi->id, $period]
                    );

                    if (!$exists) {
                        $actualValue = rand(50, 100);
                        $targetValue = $kpi->target ?? 100;
                        $achievement = $targetValue > 0 ? ($actualValue / $targetValue) * 100 : 0;

                        if ($achievement >= 90) {
                            $status = 'achieved';
                            $performanceLevel = 'excellent';
                        } elseif ($achievement >= 75) {
                            $status = 'achieved';
                            $performanceLevel = 'good';
                        } elseif ($achievement >= 60) {
                            $status = 'warning';
                            $performanceLevel = 'satisfactory';
                        } elseif ($achievement >= 45) {
                            $status = 'warning';
                            $performanceLevel = 'needs_improvement';
                        } else {
                            $status = 'critical';
                            $performanceLevel = 'unsatisfactory';
                        }

                        $submissionStatus = 'draft';
                        $submittedAt = null;
                        $reviewedAt = null;
                        $reviewedBy = null;
                        $reviewerNotes = null;

                        // Varied status for testing
                        if ($emp->id == 3) {
                            $submissionStatus = 'submitted'; // Employee 3 submitted, pending review
                            $submittedAt = Carbon::now()->subDays(2);
                        } elseif ($emp->id == 4) {
                            $submissionStatus = 'approved'; // Employee 4 approved
                            $submittedAt = Carbon::now()->subDays(5);
                            $reviewedAt = Carbon::now()->subDays(1);
                            $reviewedBy = $emp->supervisor_id; // Manager / Unit Head
                            $reviewerNotes = 'Excellent performance this month.';
                        } elseif ($emp->id == 5) {
                            $submissionStatus = 'rejected'; // Employee 5 rejected
                            $submittedAt = Carbon::now()->subDays(3);
                            $reviewedAt = Carbon::now()->subDays(1);
                            $reviewedBy = $emp->supervisor_id; // Manager / Unit Head
                            $reviewerNotes = 'Please provide more details on project completion.';
                        }

                        DB::insert(
                            'INSERT INTO employee_kpi_records (employee_id, kpi_id, period, actual_value, target_value, status, performance_level, composite_score, notes, submission_status, submitted_at, reviewed_at, reviewed_by, reviewer_notes, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $emp->id,
                                $kpi->id,
                                $period,
                                $actualValue,
                                $targetValue,
                                $status,
                                $performanceLevel,
                                $achievement,
                                'Auto-generated dummy record',
                                $submissionStatus,
                                $submittedAt,
                                $reviewedAt,
                                $reviewedBy,
                                $reviewerNotes,
                                Carbon::now(),
                                Carbon::now(),
                            ]
                        );
                        $inserted++;
                    }
                }
            }
            $this->info("✓ KPI Records synced ($inserted inserted)");

            // 10. Seed Payroll Data
            $this->info('Creating realistic payroll, presence, and task data...');
            \Artisan::call('db:seed', ['--class' => 'PayrollDummyDataSeeder']);
            $this->info('✓ Payroll dummy data synced');

            // 11. Seed Letter Data
            $this->info('Creating dummy letters for all templates...');
            \Artisan::call('db:seed', ['--class' => 'LetterDummyDataSeeder']);
            $this->info('✓ Letter dummy data synced');

            $this->info('');
            $this->info('✅ All dummy data successfully synchronized!');
            $this->info('Summary:');
            $this->info('  • Roles: 4');
            $this->info('  • Departments: 5');
            $this->info('  • Employees: 5 (with associated users)');
            $this->info('  • KPIs: 10');
            $this->info('  • Tasks: 2');
            $this->info('  • Leave Requests: 2');
            $this->info('  • Inventory Categories: 5');
            $this->info('  • Inventory Items: 5');
            $this->info('  • Letter Templates: 5');
            $this->info('  • Letter Configuration: 1');
            $this->info('  • KPI Records: ' . $inserted . ' (for 5 employees × 10 KPIs)');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
