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
        Schema::create('employee_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('old_department_id')->nullable()->constrained('departments');
            $table->foreignId('new_department_id')->nullable()->constrained('departments');
            $table->foreignId('old_role_id')->nullable()->constrained('roles');
            $table->foreignId('new_role_id')->nullable()->constrained('roles');
            $table->decimal('old_salary', 15, 2)->nullable();
            $table->decimal('new_salary', 15, 2)->nullable();
            $table->date('mutation_date');
            $table->string('type')->default('mutation'); // mutation, promotion, demotion, adjustment
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_mutations');
    }
};
