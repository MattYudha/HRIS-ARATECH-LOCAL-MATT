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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('leave_type'); // e.g., 'annual', 'sick', etc.
            $table->integer('entitlement')->default(12);
            $table->integer('taken')->default(0);
            $table->integer('balance')->default(12);
            $table->integer('year');
            $table->timestamps();
            
            // Allow only one record per employee, per leave type, per year
            $table->unique(['employee_id', 'leave_type', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
