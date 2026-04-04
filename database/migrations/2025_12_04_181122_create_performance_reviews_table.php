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
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('restrict'); // HR or Manager
            $table->string('period'); // 2025-12 or 2025-Q4, etc
            $table->decimal('overall_score', 5, 2); // 1-5 or 0-100
            $table->text('achievements')->nullable(); // What was achieved
            $table->text('areas_improvement')->nullable(); // Areas to improve
            $table->text('goals_next_period')->nullable(); // Goals for next period
            $table->text('comments')->nullable(); // Additional comments
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'rejected'])->default('draft');
            $table->dateTime('reviewed_at')->nullable(); // When the review was completed
            $table->dateTime('approved_at')->nullable(); // When it was approved
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Indexes
            $table->unique(['employee_id', 'period']);
            $table->index('period');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_reviews');
    }
};
