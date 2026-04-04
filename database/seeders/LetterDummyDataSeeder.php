<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Letter;
use App\Models\LetterTemplate;
use App\Models\Signature;
use App\Models\LetterConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LetterDummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $templates = LetterTemplate::all();
        if ($templates->isEmpty()) {
            $this->command?->warn('No letter templates found; skipping letter dummy data.');
            return;
        }

        $employees = Employee::with('user')->get();
        if ($employees->isEmpty()) {
            $this->command?->warn('No employees found; skipping letter dummy data.');
            return;
        }

        $adminUser = User::where('email', 'admin@example.com')->first();
        $managerUser = User::where('email', 'manager@example.com')->first();
        $approverId = $adminUser->id ?? ($managerUser->id ?? 1);

        $config = LetterConfiguration::first();
        $companyName = $config->company_name ?? 'PT Aratech Indonesia';

        foreach ($templates as $template) {
            // Create a pending letter for each template
            $this->createLetter($template, $employees->random(), null, 'pending');

            // Create an approved letter for each template
            $approvedLetter = $this->createLetter($template, $employees->random(), $approverId, 'approved');
            $this->addSignature($approvedLetter);

            // Create a printed letter for each template
            $printedLetter = $this->createLetter($template, $employees->random(), $approverId, 'printed');
            $this->addSignature($printedLetter);
        }

        $this->command?->info('✓ Created dummy letters for ' . $templates->count() . ' templates.');
    }

    private function createLetter(LetterTemplate $template, Employee $employee, ?int $approverId, string $status): Letter
    {
        $createdDate = Carbon::now()->subDays(rand(1, 30));
        $approvedDate = $status !== 'pending' ? $createdDate->copy()->addDays(rand(1, 3)) : null;
        $printedDate = $status === 'printed' ? ($approvedDate ? $approvedDate->copy()->addHours(rand(1, 24)) : null) : null;

        $letterNumber = null;
        if ($status !== 'pending') {
            $year = $createdDate->year;
            $month = $createdDate->format('m');
            $randNum = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            $letterNumber = "{$randNum}/{$template->type}/{$month}/{$year}";
        }

        return Letter::create([
            'user_id' => $employee->user->id,
            'approver_id' => $approverId,
            'letter_template_id' => $template->id,
            'letter_number' => $letterNumber,
            'subject' => "Dummy {$template->name} - " . Str::random(5),
            'content' => $template->content, // Handled by getFormattedContentAttribute
            'status' => $status,
            'created_date' => $createdDate,
            'approved_date' => $approvedDate,
            'printed_date' => $printedDate,
            'purpose' => 'Testing and verification of ' . $template->name,
            'start_date' => $employee->hire_date,
            'end_date' => Carbon::now()->toDateString(),
            'reason' => 'System verification dummy data',
            'days' => rand(1, 14),
            'period' => '2025/2026',
            'recommender_name' => 'System Administrator',
            'letter_type' => $template->type,
        ]);
    }

    private function addSignature(Letter $letter): void
    {
        $signatureData = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';
        
        $signature = Signature::create([
            'user_id' => $letter->approver_id,
            'signable_id' => $letter->id,
            'signable_type' => 'App\Models\Letter',
            'signature_image' => $signatureData,
            'signature_hash' => hash('sha256', $signatureData . $letter->approver_id . $letter->id . now()),
            'signed_date' => $letter->approved_date ?? now(),
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Dummy Seeder)',
            'is_verified' => true,
            'verified_at' => $letter->approved_date ?? now(),
            'verification_token' => Str::random(64),
        ]);

        // Create verification record
        $signature->verifications()->create([
            'verified_by_id' => $letter->approver_id,
            'status' => 'verified',
            'remarks' => 'Auto-verified dummy signature',
            'verification_date' => $letter->approved_date ?? now(),
        ]);
    }
}
