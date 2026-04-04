<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class UpdateAdminToPowerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find admin user and update their employee's role to Power User (id 5)
        $adminUser = User::where('email', 'admin@example.com')->first();
        
        if ($adminUser && $adminUser->employee) {
            $adminUser->employee->update([
                'role_id' => 5, // Power User role
            ]);
        }
    }
}
