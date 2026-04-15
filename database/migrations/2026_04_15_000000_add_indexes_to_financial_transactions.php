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
        Schema::table('financial_transactions', function (Blueprint $table) {
            // Primary composite index for incremental running_balance recalculation:
            // Supports: WHERE account_id = ? AND transaction_date >= ? ORDER BY transaction_date, id
            $table->index(['account_id', 'transaction_date', 'id'], 'idx_recalc_running_balance');

            // Index for type-filtered summary queries:
            // Supports: WHERE transaction_type = ? AND transaction_date >= ?
            $table->index(['transaction_type', 'transaction_date'], 'idx_trx_type_date');

            // Index for combined account + type filtering (common in reports):
            // Supports: WHERE account_id = ? AND transaction_type = ? ORDER BY transaction_date
            $table->index(['account_id', 'transaction_type', 'transaction_date'], 'idx_account_type_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_recalc_running_balance');
            $table->dropIndex('idx_trx_type_date');
            $table->dropIndex('idx_account_type_date');
        });
    }
};
