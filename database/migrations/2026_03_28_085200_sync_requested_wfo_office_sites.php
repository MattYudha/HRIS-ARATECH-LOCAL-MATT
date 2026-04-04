<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $timestamp = now();

        $officeSites = [
            [
                'name' => 'Marquee - The Plaza Office Tower',
                'location_type' => 'branch',
                'address' => 'RR4C+WV Gondangdia, Kota Jakarta Pusat, Daerah Khusus Ibukota Jakarta',
                'latitude' => -6.192553,
                'longitude' => 106.822353,
                'radius' => 1000,
                'allowed_ssids' => json_encode(['MARQUEE']),
                'status' => 'active',
                'notes' => 'Site WFO yang dapat dipilih semua karyawan.',
            ],
            [
                'name' => 'Cilandak Town Square',
                'location_type' => 'branch',
                'address' => 'PQ5X+FR Cilandak Bar., Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta',
                'latitude' => -6.291389,
                'longitude' => 106.799722,
                'radius' => 1000,
                'allowed_ssids' => json_encode(['CITOS']),
                'status' => 'active',
                'notes' => 'Site WFO yang dapat dipilih semua karyawan.',
            ],
            [
                'name' => 'Kantor TEST',
                'location_type' => 'other',
                'address' => 'Lokasi uji WFO internal',
                'latitude' => -6.367914,
                'longitude' => 106.644239,
                'radius' => 1000,
                'allowed_ssids' => json_encode(['SERHAN']),
                'status' => 'active',
                'notes' => 'Site WFO yang dapat dipilih semua karyawan.',
            ],
        ];

        foreach ($officeSites as $officeSite) {
            DB::table('office_locations')->updateOrInsert(
                ['name' => $officeSite['name']],
                array_merge($officeSite, [
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                    'deleted_at' => null,
                ])
            );
        }
    }

    public function down(): void
    {
        DB::table('office_locations')
            ->whereIn('name', [
                'Marquee - The Plaza Office Tower',
                'Cilandak Town Square',
                'Kantor TEST',
            ])
            ->delete();
    }
};
