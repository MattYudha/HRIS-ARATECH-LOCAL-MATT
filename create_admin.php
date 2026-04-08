<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    // Pastikan Role dan Department ada
    $superAdminRole = Role::firstOrCreate(['title' => 'Super Admin'], ['description' => 'System Administrator']);
    $hrDept = Department::firstOrCreate(['name' => 'Human Resources'], ['description' => 'HR Department', 'status' => 'active']);

    // Buat/Update User
    $user = User::updateOrCreate(
        ['email' => 'admin@aratech.id'],
        [
            'name'     => 'Administrator',
            'password' => Hash::make('Admin@1234'),
        ]
    );

    // Buat/Update Employee yang terhubung dengan User dan Role Super Admin
    $employee = Employee::withTrashed()->updateOrCreate(
        ['email' => 'admin@aratech.id'],
        [
            'user_id' => $user->id,
            'nik' => 'ADMIN-001',
            'fullname' => 'System Administrator',
            'phone_number' => '081234567890',
            'address' => 'Jakarta',
            'department_id' => $hrDept->id,
            'role_id' => $superAdminRole->id,
            'hire_date' => date('Y-m-d'),
            'status' => 'active',
            'employee_status' => 'permanent',
            'npwp' => '00.000.000.0-000.000',
            'salary' => 10000000,
            'deleted_at' => null // untuk un-trash jika perlu
        ]
    );

    // Assign id employee di user
    $user->employee_id = $employee->id;
    $user->save();

    file_put_contents('admin_error.txt', "SUCCESS");
    echo "SUCCESS";
} catch (\Exception $e) {
    file_put_contents('admin_error.txt', $e->getMessage());
    echo "ERROR";
}
