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
        Schema::table('employees', function (Blueprint $table) {
            $table->date('resign_date')->nullable()->after('hire_date');
            $table->date('permanent_date')->nullable()->after('resign_date');
            $table->date('contract_expiry')->nullable()->after('permanent_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['resign_date', 'permanent_date', 'contract_expiry']);
        });
    }
};
