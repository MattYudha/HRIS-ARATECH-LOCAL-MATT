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
        Schema::create('signature_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('signature_id');
            $table->unsignedBigInteger('verified_by_id'); // HR/Admin who verified
            $table->enum('status', ['verified', 'rejected', 'pending'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamp('verification_date')->useCurrent();
            $table->timestamps();
            
            $table->foreign('signature_id')->references('id')->on('signatures')->onDelete('cascade');
            $table->foreign('verified_by_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['signature_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signature_verifications');
    }
};
