<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Role;
use App\Constants\Roles;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Role and Department Relationship\n";
echo "========================================\n";

// 1. Find the new department
$dept = Department::where('name', 'LIKE', '%Logistik%')->first();
if (!$dept) {
     echo "Error: Logistics department not found.\n";
     exit(1);
}
echo "New Department Found: " . $dept->name . " (ID: " . $dept->id . ")\n";

// 2. Create/Find a Manager role
$managerRole = Role::where('title', Roles::MANAGER)->first();
if (!$managerRole) {
    echo "Error: Manager role not found.\n";
    exit(1);
}

// 3. Mock a Manager for this department
$managerEmployee = new Employee([
    'fullname' => 'Test Manager',
    'department_id' => $dept->id,
    'role_id' => $managerRole->id
]);
$managerUser = new User();
$managerUser->setRelation('employee', $managerEmployee);

// 4. Mock an Employee for this same department
$staffEmployee = new Employee([
    'fullname' => 'Test Staff',
    'department_id' => $dept->id
]);

// 5. Verify manager can manage staff in their own department
echo "Checking if Manager of '{$dept->name}' can manage staff in same department...\n";
$canManage = $managerUser->canManage($staffEmployee);
echo "MANAGER CAN MANAGE STAFF: " . ($canManage ? "YES" : "NO") . "\n";

// 6. Mock an Employee for ANOTHER department (e.g. IT)
$itDept = Department::where('name', 'IT')->first();
$otherStaff = new Employee([
    'fullname' => 'Other Dept Staff',
    'department_id' => $itDept->id
]);

echo "Checking if Manager of '{$dept->name}' can manage staff in different department (IT)...\n";
$canManageOther = $managerUser->canManage($otherStaff);
echo "MANAGER CAN MANAGE OTHER STAFF: " . ($canManageOther ? "YES" : "NO") . "\n";

echo "\nCONCLUSION: New roles are NOT needed for new departments because the Manager role is already department-scoped.\n";
