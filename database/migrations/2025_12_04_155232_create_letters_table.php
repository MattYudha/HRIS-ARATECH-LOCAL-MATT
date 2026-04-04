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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('letter_template_id')->nullable()->constrained('letter_templates')->onDelete('set null');
            $table->string('letter_number')->unique()->nullable();
            $table->string('subject');
            $table->longText('content');
            $table->string('letter_type')->default('official'); // official, memo, notice
            $table->enum('status', ['draft', 'pending', 'approved', 'printed'])->default('draft');
            $table->date('created_date');
            $table->timestamp('approved_date')->nullable();
            $table->timestamp('printed_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
