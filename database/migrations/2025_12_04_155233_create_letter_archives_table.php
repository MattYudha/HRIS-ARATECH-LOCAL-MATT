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
        Schema::create('letter_archives', function (Blueprint $table) {
            $table->id();
            $table->integer('month');
            $table->integer('year');
            $table->integer('total_letters')->default(0);
            $table->integer('approved_letters')->default(0);
            $table->integer('printed_letters')->default(0);
            $table->longText('summary')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_archives');
    }
};
