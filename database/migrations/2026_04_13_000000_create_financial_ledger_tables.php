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
        // 1. Table untuk Master Data Entitas (Pengirim / Penerima)
        Schema::create('financial_entities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama Entitas, misal: Bank EEEE, Laznas ABC, Hendratno');
            $table->enum('type', ['internal', 'bank', 'vendor', 'employee', 'tax_office', 'other'])->default('other');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Table untuk Chart of Accounts (Akun Terkait)
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode Akun, misal 101, 201');
            $table->string('name')->comment('Nama Akun, misal: Modal Awal, ZISWAF Tidak Rutin, Tabungan Bank-0001');
            $table->enum('category', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Table Utama: Financial Transactions (Buku Kas / Ledger)
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->text('description')->comment('Keterangan transaksi');
            
            // Relasi ke Entitas Pengirim (Sender)
            $table->foreignId('sender_entity_id')
                  ->nullable()
                  ->constrained('financial_entities')
                  ->nullOnDelete();
                  
            // Relasi ke Entitas Penerima (Receiver)
            $table->foreignId('receiver_entity_id')
                  ->nullable()
                  ->constrained('financial_entities')
                  ->nullOnDelete();
                  
            // Relasi ke Chart of Accounts (Akun)
            $table->foreignId('account_id')
                  ->constrained('financial_accounts')
                  ->restrictOnDelete();
                  
            // Tipe dan Nominal Transaksi
            $table->enum('transaction_type', ['debit', 'kredit']);
            $table->decimal('amount', 15, 2)->default(0);
            
            // Running saldo (untuk mempermudah query jika saldo dihitung per baris)
            $table->decimal('running_balance', 15, 2)->default(0);
            
            // Markings untuk akhir bulan/akhir tahun seperti di excel
            $table->boolean('is_end_of_month')->default(false);
            $table->boolean('is_end_of_year')->default(false);
            
            // Siapa yang menginput
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('financial_accounts');
        Schema::dropIfExists('financial_entities');
    }
};
