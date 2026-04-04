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
        Schema::table('inventories', function (Blueprint $table) {
            $table->integer('min_stock_threshold')->default(0)->after('quantity');
            $table->string('image_path')->nullable()->after('description');
            $table->softDeletes();
        });

        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('inventory_usage_logs', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_usage_logs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('inventory_requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('inventories', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['min_stock_threshold', 'image_path']);
        });
    }
};
