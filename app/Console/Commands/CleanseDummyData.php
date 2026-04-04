<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CleanseDummyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanse-dummy-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely cleanse dummy data from all transactional tables while preserving master data (Roles, Departments, etc.)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('!!! WARNING !!!');
        $this->warn('This command will PERMANENTLY DELETE all dummy transactional data across 25+ tables (Employees, Users, Leaves, Logs, Tasks, etc.)');
        $this->warn('Master data (Roles, Departments, Office Locations, Categories, Education Levels, etc.) will be preserved.');
        
        if (!$this->confirm('Are you absolutely sure you want to proceed? (MAKE SURE YOU HAVE A DATABASE BACKUP!)')) {
            $this->info('Operation cancelled.');
            return;
        }

        $this->info('Starting cleansing process...');

        // Comprehensive list of transactional tables to cleanse
        $tables = [
            // Transactional/Log tables
            'task_comments',
            'tasks',
            'incidents',
            'leave_requests',
            'leave_balances',
            'leaves',
            'inventory_usage_logs',
            'inventory_requests',
            'procurement_items',
            'procurements',
            'inventory_dispatches',
            'logistics_shipments',
            'presences',
            'attendances',
            'salaries',
            'payroll',
            'employee_kpi_records',
            'employee_mutations',
            'employee_update_approvals',
            'performance_reviews',
            'signature_verifications',
            'signatures',
            'letters',
            'letter_archives',
            'suspicious_activities',
            'failed_jobs',
            
            // Employee data (FK dependencies)
            'employee_families',
            'document_identities',
            'bank_accounts',
            
            // Core Identity
            'users',
            'employees',
            
            // Cache/Session leftovers
            'sessions'
        ];

        Schema::disableForeignKeyConstraints();

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->line(" <fg=green>✔</> Table [{$table}] cleansed.");
            } else {
                $this->line(" <fg=yellow>⚠</> Table [{$table}] not found, skipped.");
            }
        }

        Schema::enableForeignKeyConstraints();

        $this->info('Cleansing completed successfully!');
        $this->line('');
        $this->warn('Next steps to setup your first administrator:');
        $this->line('1. Run: php artisan tinker');
        $this->line('2. Paste: \App\Models\User::factory()->create([\'email\' => \'admin@company.id\', \'password\' => bcrypt(\'masukkan-password-anda\')]);');
        $this->line('3. Logout and Login with those credentials.');
        $this->line('');
        $this->info('Your HR AdministratorIS is now ready for production data entry!');
    }
}
