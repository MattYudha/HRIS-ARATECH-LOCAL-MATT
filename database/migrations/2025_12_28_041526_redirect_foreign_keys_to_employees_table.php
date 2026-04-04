<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. bank_accounts
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 2. employee_positions
        Schema::table('employee_positions', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('employee_positions', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 3. salaries
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('salaries', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 4. employee_families
        Schema::table('employee_families', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('employee_families', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 5. attendances
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 6. leaves
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        // 7. document_identities
        Schema::table('document_identities', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
        });
        Schema::table('document_identities', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse is not strictly necessary for this sync task, but we can restore to employees_v2 if needed.
        // For simplicity, we'll leave it as is since employees_v2 is intended to be replaced.
    }
};
