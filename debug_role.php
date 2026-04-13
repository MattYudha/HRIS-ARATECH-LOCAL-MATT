<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$user = User::where('email', 'admin@aratech.id')->first();
if ($user) {
    echo "USER: " . $user->email . "\n";
    if ($user->employee) {
        echo "EMPLOYEE: " . $user->employee->fullname . "\n";
        if ($user->employee->role) {
            echo "ROLE TITLE: [" . $user->employee->role->title . "]\n";
        } else {
            echo "ROLE: NONE\n";
        }
    } else {
        echo "EMPLOYEE: NONE\n";
    }
} else {
    echo "USER NOT FOUND\n";
}

$allRoles = \App\Models\Role::all()->pluck('title')->toArray();
echo "ALL ROLES IN DB: " . implode(', ', $allRoles) . "\n";
