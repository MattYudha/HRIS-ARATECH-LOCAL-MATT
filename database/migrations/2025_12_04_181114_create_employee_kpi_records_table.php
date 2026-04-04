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
        Schema::create('employee_kpi_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('kpi_id')->constrained('kpis')->onDelete('cascade');
            $table->string('period'); // 2025-12 (year-month) or 2025-Q1, etc
            $table->decimal('actual_value', 10, 2); // Actual measured value
            $table->decimal('target_value', 10, 2); // Target for this period
            $table->decimal('previous_value', 10, 2)->nullable(); // Previous period value for comparison
            $table->enum('status', ['achieved', 'warning', 'critical', 'na'])->default('na');
            $table->text('notes')->nullable();
            $table->string('calculation_method')->nullable(); // How was this calculated
            $table->timestamps();
            
            // Composite score (weighted average of all KPIs for the employee)
            $table->decimal('composite_score', 5, 2)->nullable();
            $table->enum('performance_level', ['excellent', 'good', 'satisfactory', 'needs_improvement', 'unsatisfactory'])->nullable();
            
            // Indexes
            $table->unique(['employee_id', 'kpi_id', 'period']);
            $table->index(['period', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_kpi_records');
    }
};
