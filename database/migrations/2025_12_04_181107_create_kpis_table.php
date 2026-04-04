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
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ATT_RATE, TASK_COMP, etc
            $table->string('name'); // Attendance Rate, Task Completion Rate, etc
            $table->string('category'); // Attendance, Productivity, Leave, Salary, Department, Behavior, Quality
            $table->text('description')->nullable();
            $table->text('formula')->nullable(); // Formula description
            $table->decimal('target_value', 8, 2)->default(0); // Target value (e.g., 95 for 95%)
            $table->decimal('min_value', 8, 2)->default(0); // Minimum acceptable value
            $table->decimal('max_value', 8, 2)->default(100); // Maximum possible value
            $table->decimal('weight', 5, 2)->default(1); // Weight for composite scoring
            $table->string('unit')->nullable(); // %, days, IDR, etc
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpis');
    }
};
