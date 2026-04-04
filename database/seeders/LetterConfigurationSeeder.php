<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LetterConfiguration;

class LetterConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LetterConfiguration::create([
            'company_name' => 'PT Aratechnology Indonesia',
            'company_address' => 'Jl. Merdeka No. 123, Jakarta Pusat 12190',
            'company_phone' => '(021) 1234-5678',
            'company_email' => 'info@aratechnology.id',
            'company_website' => 'www.aratechnology.id',
            'letterhead_footer' => 'PT Aratechnology Indonesia | Jl. Merdeka No. 123, Jakarta | www.aratechnology.id',
            'letter_number_format' => '{NUMBER}/{DEPT}/{MONTH}/{YEAR}',
            'current_number' => 0,
            'is_active' => true,
        ]);
    }
}
