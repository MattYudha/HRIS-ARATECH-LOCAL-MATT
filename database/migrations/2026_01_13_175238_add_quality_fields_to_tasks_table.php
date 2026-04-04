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
            $table->integer('quality_rating')->nullable()->after('completed_at')->comment('1-5 scale');
            $table->text('quality_notes')->nullable()->after('quality_rating');
            $table->foreignId('reviewed_by')->nullable()->after('quality_notes')->constrained('employees');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['quality_rating', 'quality_notes', 'reviewed_by', 'reviewed_at']);
        });
    }
};
