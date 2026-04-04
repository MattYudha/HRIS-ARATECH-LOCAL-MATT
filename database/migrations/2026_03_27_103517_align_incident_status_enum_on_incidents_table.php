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
        DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('reported', 'under_investigation', 'resolved', 'archived', 'pending', 'investigating', 'closed') NOT NULL DEFAULT 'pending'");

        DB::table('incidents')->where('status', 'reported')->update(['status' => 'pending']);
        DB::table('incidents')->where('status', 'under_investigation')->update(['status' => 'investigating']);
        DB::table('incidents')->where('status', 'archived')->update(['status' => 'closed']);

        DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('pending', 'investigating', 'resolved', 'closed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('reported', 'under_investigation', 'resolved', 'archived', 'pending', 'investigating', 'closed') NOT NULL DEFAULT 'reported'");

        DB::table('incidents')->where('status', 'pending')->update(['status' => 'reported']);
        DB::table('incidents')->where('status', 'investigating')->update(['status' => 'under_investigation']);
        DB::table('incidents')->where('status', 'closed')->update(['status' => 'archived']);

        DB::statement("ALTER TABLE incidents MODIFY COLUMN status ENUM('reported', 'under_investigation', 'resolved', 'archived') NOT NULL DEFAULT 'reported'");
    }
};
