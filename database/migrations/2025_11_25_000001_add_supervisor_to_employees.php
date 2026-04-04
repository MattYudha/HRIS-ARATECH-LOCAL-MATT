<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'supervisor_id')) {
                $table->unsignedBigInteger('supervisor_id')->nullable()->after('role_id');
                // optionally add foreign key if desired (commented out to avoid strict FK issues)
                // $table->foreign('supervisor_id')->references('id')->on('employees')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'supervisor_id')) {
                // drop foreign key if exists
                // $table->dropForeign(['supervisor_id']);
                $table->dropColumn('supervisor_id');
            }
        });
    }
};
