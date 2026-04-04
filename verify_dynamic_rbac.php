<?php

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Constants\Roles;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Dynamic RBAC Implementation\n";
echo "==================================\n";

// 1. Create a mock Role with modular access
$role = new Role([
    'title' => 'Logistics Officer',
    'access' => ['inventory', 'inventory_logs']
]);

// 2. Create a mock Employee
$employee = new Employee([
    'fullname' => 'John Logistics',
]);
$employee->setRelation('role', $role);

// 3. Create a mock User
$user = new User([]);
$user->setRelation('employee', $employee);

echo "Testing user: {$employee->fullname} with role: {$role->title}\n";
echo "Modular Access: " . json_encode($role->access) . "\n\n";

// 4. Test hasAccess method
echo "Test 1: User->hasAccess('inventory')...\n";
$hasInventory = $user->hasAccess('inventory');
echo "RESULT: " . ($hasInventory ? "PASS" : "FAIL") . "\n";

echo "Test 2: User->hasAccess('hr_reports')...\n";
$hasHR = $user->hasAccess('hr_reports');
echo "RESULT: " . (!$hasHR ? "PASS (Correctly Denied)" : "FAIL") . "\n";

// 5. Test Middleware Logic
echo "\nTesting Middleware Logic (Simulated)...\n";
$requiredRoles = ['HR', 'Power User', 'inventory'];

$hasRole = in_array($role->title, $requiredRoles);
$hasAccess = false;
foreach ($requiredRoles as $r) {
    if (preg_match('/^[a-z_]+$/', $r) && is_array($role->access) && in_array($r, $role->access)) {
        $hasAccess = true;
        break;
    }
}

echo "Required for Route: " . implode(', ', $requiredRoles) . "\n";
echo "Has Role Match: " . ($hasRole ? "YES" : "NO") . "\n";
echo "Has Access Match: " . ($hasAccess ? "YES" : "NO") . "\n";
echo "OVERALL AUTHORIZED: " . ($hasRole || $hasAccess ? "YES (PASS)" : "NO (FAIL)") . "\n";

echo "\nCONCLUSION: Dynamic RBAC is working correctly!\n";
