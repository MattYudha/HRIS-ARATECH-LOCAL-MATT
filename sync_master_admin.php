<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

echo "--- SYNCING MASTER ADMIN ROLE ---\n";

try {
    DB::beginTransaction();

    // 1. Update title in roles table if it exists as 'Super Admin'
    $role = Role::where('title', 'Super Admin')->first();
    if ($role) {
        $role->update(['title' => 'Master Admin', 'description' => 'System Administrator (Master)']);
        echo "✅ Updated Role: 'Super Admin' -> 'Master Admin'\n";
    } else {
        // Ensure it exists
        $role = Role::firstOrCreate(
            ['title' => 'Master Admin'],
            ['description' => 'System Administrator (Master)']
        );
        echo "ℹ️ Role 'Master Admin' found/created.\n";
    }

    // 2. Double check admin@aratech.id
    $user = User::where('email', 'admin@aratech.id')->first();
    if ($user && $user->employee) {
        $user->employee->update(['role_id' => $role->id]);
        echo "✅ User admin@aratech.id synced with Master Admin role.\n";
    }

    DB::commit();
    echo "--- DONE ---\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
