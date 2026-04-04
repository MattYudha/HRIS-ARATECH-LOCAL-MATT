<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LetterTemplate;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LetterTemplate::create([
            'name' => 'Official Letter',
            'description' => 'Standard official company letter',
            'content' => '<p>[Letterhead]</p><p>Kepada,<br/>[Recipient]</p><p>[Content]</p><p>Hormat kami,<br/>PT Aratechnology Indonesia</p>',
            'type' => 'official',
            'is_active' => true,
        ]);
        LetterTemplate::create([
            'name' => 'Memorandum',
            'description' => 'Internal memo template',
            'content' => '<p>MEMORANDUM<br/><br/>TO: [Recipient]<br/>FROM: [Sender]<br/>DATE: [Date]<br/>RE: [Subject]<br/><br/>[Content]</p>',
            'type' => 'memo',
            'is_active' => true,
        ]);
        LetterTemplate::create([
            'name' => 'Notice',
            'description' => 'General notice template',
            'content' => '<p>NOTICE<br/><br/>To All Employees,<br/><br/>[Content]<br/><br/>Management</p>',
            'type' => 'notice',
            'is_active' => true,
        ]);
    }
}
