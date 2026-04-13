<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_claims', function (Blueprint $table) {
            $table->id();

            // Claimant
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            // Claim details
            $table->string('title');                          // Judul klaim
            $table->string('category');                       // transport, meals, operational, other
            $table->decimal('amount', 18, 2);
            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable();    // bukti struk/invoice

            // Status workflow
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            // Auto-journal reference (set when approved)
            $table->foreignId('transaction_id')->nullable()
                  ->constrained('financial_transactions')->nullOnDelete();

            // CoA account to debit when approved
            $table->foreignId('account_id')->nullable()
                  ->constrained('financial_accounts')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_claims');
    }
};
