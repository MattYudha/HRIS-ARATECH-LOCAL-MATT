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
        Schema::table('kpis', function (Blueprint $table) {
            $table->string('metric_category')->nullable()->after('code')->comment('attendance, productivity, quality, etc');
            $table->string('metric_key')->nullable()->after('metric_category')->comment('attendance_rate, completed_tasks_count, etc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kpis', function (Blueprint $table) {
            $table->dropColumn(['metric_category', 'metric_key']);
        });
    }
};
