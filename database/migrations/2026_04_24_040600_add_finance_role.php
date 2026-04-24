<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleTitle = \App\Constants\Roles::FINANCE;
        
        $exists = DB::table('roles')->where('title', $roleTitle)->exists();
        
        if (!$exists) {
            DB::table('roles')->insert([
                'title' => $roleTitle,
                'description' => 'Finance Operator and Viewer',
                'access' => json_encode(['finance']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversing to prevent accidental data loss for users assigned this role
    }
};
