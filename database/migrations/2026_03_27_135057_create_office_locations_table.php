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
        if (Schema::hasTable('office_locations')) {
            return;
        }

        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location_type');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('radius');
            $table->json('allowed_ssids')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op: this legacy duplicate migration should never drop the canonical office_locations table.
    }
};
