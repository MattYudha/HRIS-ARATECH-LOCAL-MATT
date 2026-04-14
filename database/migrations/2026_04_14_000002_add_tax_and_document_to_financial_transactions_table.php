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
            $table->decimal('dpp_amount', 15, 2)->nullable()->after('amount')->comment('Dasar Pengenaan Pajak (DPP)');
            $table->enum('tax_type', ['none', 'ppn', 'pph_21', 'pph_23', 'pph_4_ayat_2'])->default('none')->after('dpp_amount');
            $table->decimal('tax_amount', 15, 2)->nullable()->after('tax_type')->comment('Nominal Pajak yg dipotong/dibayarkan');
            $table->string('document_path')->nullable()->after('tax_amount')->comment('Path for uploaded Bukti Potong/Faktur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('financial_transactions', function (Blueprint $table) {
            $table->dropColumn(['dpp_amount', 'tax_type', 'tax_amount', 'document_path']);
        });
    }
};
