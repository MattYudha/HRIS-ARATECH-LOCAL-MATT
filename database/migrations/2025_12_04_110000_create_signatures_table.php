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
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // The person signing
            $table->morphs('signable'); // Polymorphic relation to any model
            $table->longText('signature_image'); // Base64 encoded signature
            $table->string('signature_hash')->unique(); // Cryptographic hash
            $table->timestamp('signed_date')->useCurrent();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('verification_token')->nullable()->unique();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('verification_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
