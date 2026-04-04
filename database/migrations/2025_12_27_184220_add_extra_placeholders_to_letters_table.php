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
        Schema::table('letters', function (Blueprint $table) {
            $table->text('reason')->nullable()->after('end_date');
            $table->string('days')->nullable()->after('reason');
            $table->string('period')->nullable()->after('days');
            $table->string('recommender_name')->nullable()->after('period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->dropColumn(['reason', 'days', 'period', 'recommender_name']);
        });
    }
};
