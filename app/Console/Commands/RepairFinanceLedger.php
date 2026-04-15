<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FinancialTransaction;
use App\Models\FinancialAccount;
use Illuminate\Support\Facades\DB;

class RepairFinanceLedger extends Command
{
    /**
     * php artisan finance:repair-ledger
     * php artisan finance:repair-ledger --account=5
     */
    protected $signature = 'finance:repair-ledger {--account=all : Account ID to fix, or "all" to fix every account}';

    protected $description = 'Idempotent: Rebuilds correct running_balance per account from transaction history. Safe to run multiple times.';

    public function handle()
    {
        $accountOpt = $this->option('account');

        if ($accountOpt === 'all') {
            $accounts = FinancialAccount::orderBy('id')->pluck('id');
            $this->info("Rebuilding ledger for all {$accounts->count()} accounts...\n");
        } else {
            if (!is_numeric($accountOpt)) {
                $this->error("Invalid account ID: '{$accountOpt}'. Use --account=<id> or --account=all.");
                return Command::FAILURE;
            }
            $accounts = collect([(int) $accountOpt]);
            $this->info("Rebuilding ledger for account #{$accountOpt}...\n");
        }

        $bar = $this->output->createProgressBar($accounts->count());
        $bar->start();

        foreach ($accounts as $accountId) {
            $this->rebuildAccount($accountId);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('✅ Ledger rebuilt successfully. All running balances are synchronized.');

        return Command::SUCCESS;
    }

    private function rebuildAccount(int $accountId): void
    {
        DB::transaction(function () use ($accountId) {
            // Serialize this account's repair against concurrent writes
            FinancialAccount::where('id', $accountId)->lockForUpdate()->first();

            $balance = '0.00';
            $dirtyMonths = [];

            // Idempotently process ALL transactions for this account via chunk
            // to avoid OOM on large datasets. Chunk size = 1000.
            FinancialTransaction::where('account_id', $accountId)
                ->orderBy('transaction_date', 'asc')
                ->orderBy('id', 'asc')
                ->chunk(1000, function ($chunk) use (&$balance, &$dirtyMonths) {
                    foreach ($chunk as $trx) {
                        // Use bcmath to avoid floating-point precision errors
                        if ($trx->transaction_type === 'debit') {
                            $balance = bcadd($balance, (string) $trx->amount, 2);
                        } else {
                            $balance = bcsub($balance, (string) $trx->amount, 2);
                        }

                        $date = \Carbon\Carbon::parse($trx->transaction_date);
                        $monthKey = $date->format('Y-m');
                        $dirtyMonths[$monthKey] = true;

                        // Set running balance, clear EOM/EOY flags (repaired below idempotently)
                        $trx->running_balance = $balance;
                        $trx->is_end_of_month = false;
                        $trx->is_end_of_year  = false;
                        $trx->saveQuietly();
                    }
                });

            // Repair End of Month / End of Year flags for each affected month
            foreach (array_keys($dirtyMonths) as $monthKey) {
                $year  = (int) substr($monthKey, 0, 4);
                $month = (int) substr($monthKey, 5, 2);

                $lastTrxInMonth = FinancialTransaction::where('account_id', $accountId)
                    ->whereYear('transaction_date', $year)
                    ->whereMonth('transaction_date', $month)
                    ->orderBy('transaction_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                if ($lastTrxInMonth) {
                    $lastTrxInMonth->is_end_of_month = true;
                    $lastTrxInMonth->is_end_of_year  = ($month === 12);
                    $lastTrxInMonth->saveQuietly();
                }
            }
        });
    }
}
