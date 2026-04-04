<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run comprehensive data sync command which creates all dummy data
        // This includes roles, departments, employees, KPIs, templates, etc.
        \Artisan::call('sync:dummy-data');
    }
}
