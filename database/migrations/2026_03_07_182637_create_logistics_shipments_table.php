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
        Schema::create('logistics_shipments', function (Blueprint $table) {
            $table->id();
            $table->morphs('trackable');
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->enum('status', ['pending', 'in_transit', 'delivered', 'cancelled'])->default('pending');
            $table->timestamp('estimated_arrival')->nullable();
            $table->timestamp('actual_arrival')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logistics_shipments');
    }
};
