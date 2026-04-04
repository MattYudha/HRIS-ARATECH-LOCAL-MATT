<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('location_type')->default('other');
            $table->text('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('radius')->default((int) env('PRESENCE_LOCATION_RADIUS', 1000));
            $table->json('allowed_ssids')->nullable();
            $table->string('status')->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('office_locations')->insert([
            'name' => 'Kantor Pusat',
            'location_type' => 'head_office',
            'address' => null,
            'latitude' => (float) env('PRESENCE_OFFICE_LAT', -6.3623194),
            'longitude' => (float) env('PRESENCE_OFFICE_LON', 106.6476751),
            'radius' => (int) env('PRESENCE_LOCATION_RADIUS', 1000),
            'allowed_ssids' => json_encode(['UNPAM VIKTOR', 'Serhan 2', 'Serhan', 'S53s']),
            'status' => 'active',
            'notes' => 'Lokasi awal yang diambil dari konfigurasi presensi lama.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_locations');
    }
};
