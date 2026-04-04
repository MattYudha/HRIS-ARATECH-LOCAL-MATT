<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'manager5@example.com')->first();

if ($user) {
    $newPassword = '123';
    $user->password = Illuminate\Support\Facades\Hash::make($newPassword);
    $user->save();
    
    echo "Password reset successfully!\n";
    echo "Email: {$user->email}\n";
    echo "Password: {$newPassword}\n";
} else {
    echo "user not found!\n";
}
