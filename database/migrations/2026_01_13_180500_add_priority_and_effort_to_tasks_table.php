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
        Schema::table('tasks', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium')->after('status');
            $table->integer('estimated_hours')->nullable()->after('priority')->comment('Estimated effort in hours');
            $table->integer('actual_hours')->nullable()->after('estimated_hours')->comment('Actual time spent in hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['priority', 'estimated_hours', 'actual_hours']);
        });
    }
};
