<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Constants\Roles;

echo "Verifikasi Authorization Helper...\n";

// 1. Cek User Power User
$powerUser = User::whereHas('employee.role', function($q) {
    $q->where('title', Roles::POWER_USER);
})->first();

if ($powerUser) {
    echo "Found Power User: " . $powerUser->name . "\n";
    echo "- isPowerUser(): " . ($powerUser->isPowerUser() ? 'PASS' : 'FAIL') . "\n";
    echo "- isAdmin(): " . ($powerUser->isAdmin() ? 'PASS' : 'FAIL') . "\n";
    echo "- isSupervisor(): " . ($powerUser->isSupervisor() ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "WARNING: No Power User found.\n";
}

// 2. Cek User Manager
$managerUser = User::whereHas('employee.role', function($q) {
    $q->where('title', Roles::MANAGER);
})->first();

if ($managerUser) {
    echo "\nFound Manager: " . $managerUser->name . "\n";
    echo "- isManager(): " . ($managerUser->isManager() ? 'PASS' : 'FAIL') . "\n";
    echo "- isAdmin(): " . ($managerUser->isAdmin() ? 'FAIL (Expected)' : 'PASS') . "\n";
    echo "- isSupervisor(): " . ($managerUser->isSupervisor() ? 'PASS' : 'FAIL') . "\n";
} else {
    echo "WARNING: No Manager found.\n";
}

// 3. Cek Employee Policy logic for View
if ($powerUser && $managerUser) {
    $canManage = $powerUser->canManage($managerUser->employee);
    echo "\nTest Power User checks Manager:\n";
    echo "- canManage(): " . ($canManage ? 'PASS' : 'FAIL') . "\n";
}

echo "\nVerification Done.\n";
