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
            $table->enum('item_type', ['habis_pakai', 'tidak_habis_pakai'])->default('tidak_habis_pakai')->after('inventory_category_id');
            $table->string('area')->nullable()->after('location');
            $table->string('room')->nullable()->after('area');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'area', 'room']);
        });
    }
};
