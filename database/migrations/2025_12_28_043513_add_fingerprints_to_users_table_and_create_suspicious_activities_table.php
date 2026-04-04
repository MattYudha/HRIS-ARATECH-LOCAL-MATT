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
        Schema::table('users', function (Blueprint $table) {
            $table->string('browser_fingerprint_desktop')->nullable()->after('password');
            $table->string('browser_fingerprint_mobile')->nullable()->after('browser_fingerprint_desktop');
        });

        Schema::create('suspicious_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type'); // e.g., 'fake_gps', 'invalid_ssid', 'wrong_fingerprint'
            $table->text('details')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspicious_activities');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['browser_fingerprint_desktop', 'browser_fingerprint_mobile']);
        });
    }
};
