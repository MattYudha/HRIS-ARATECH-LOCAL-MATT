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
        Schema::create('role_kpi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('kpi_id')->constrained('kpis')->onDelete('cascade');
            $table->decimal('target_value', 10, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['role_id', 'kpi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_kpi');
    }
};
