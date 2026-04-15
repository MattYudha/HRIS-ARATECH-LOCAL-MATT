<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialEntity;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FinancialTransactionController extends Controller
{
    /**
     * Apply common filters efficiently to ensure summary and table data are in sync.
     */
    private function applyTransactionFilters(Request $request, $query)
    {
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        return $query;
    }

    /**
     * Display a listing of transactions (Buku Kas Ledger).
     */
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['senderEntity', 'receiverEntity', 'account', 'creator'])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        $query = $this->applyTransactionFilters($request, $query);

        $transactions = $query->paginate(20)->withQueryString();

        // Summary totals: Clone query to perfectly match table filters
        $summaryQuery = FinancialTransaction::query();
        $summaryQuery = $this->applyTransactionFilters($request, $summaryQuery);

        $totalDebit  = (clone $summaryQuery)->where('transaction_type', 'debit')->sum('amount');
        $totalKredit = (clone $summaryQuery)->where('transaction_type', 'kredit')->sum('amount');

        $accounts  = FinancialAccount::orderBy('code')->get();

        return view('finance.transactions.index', compact(
            'transactions', 'totalDebit', 'totalKredit', 'accounts'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create()
    {
        $entities = FinancialEntity::orderBy('name')->get();
        $accounts = FinancialAccount::orderBy('code')->get();
        return view('finance.transactions.create', compact('entities', 'accounts'));
    }

    /**
     * Store a newly created transaction and recalculate running balances.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_date'  => 'required|date',
            'description'       => 'required|string|max:500',
            'sender_entity_id'  => 'nullable|exists:financial_entities,id',
            'receiver_entity_id'=> 'nullable|exists:financial_entities,id',
            'account_id'        => 'required|exists:financial_accounts,id',
            'transaction_type'  => 'required|in:debit,kredit',
            'amount'            => 'required|numeric|min:0',
            'dpp_amount'        => 'nullable|numeric|min:0',
            'tax_type'          => 'nullable|in:none,ppn,pph_21,pph_23,pph_4_ayat_2',
            'tax_amount'        => 'nullable|numeric|min:0',
            'document'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('document')) {
            $validated['document_path'] = $request->file('document')->store('finance/documents', 'local');
        }

        $validated['created_by'] = Auth::id();

        DB::transaction(function () use ($validated) {
            // Lock account to prevent concurrent modifications
            FinancialAccount::where('id', $validated['account_id'])->lockForUpdate()->first();
            
            $transaction = FinancialTransaction::create($validated);
            $this->recalculateRunningBalance($transaction->account_id, $transaction->transaction_date);
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Show the form for editing a transaction.
     */
    public function edit(FinancialTransaction $transaction)
    {
        $entities = FinancialEntity::orderBy('name')->get();
        $accounts = FinancialAccount::orderBy('code')->get();
        return view('finance.transactions.edit', compact('transaction', 'entities', 'accounts'));
    }

    /**
     * Update a transaction and recalculate running balances.
     */
    public function update(Request $request, FinancialTransaction $transaction)
    {
        $validated = $request->validate([
            'transaction_date'  => 'required|date',
            'description'       => 'required|string|max:500',
            'sender_entity_id'  => 'nullable|exists:financial_entities,id',
            'receiver_entity_id'=> 'nullable|exists:financial_entities,id',
            'account_id'        => 'required|exists:financial_accounts,id',
            'transaction_type'  => 'required|in:debit,kredit',
            'amount'            => 'required|numeric|min:0',
            'dpp_amount'        => 'nullable|numeric|min:0',
            'tax_type'          => 'nullable|in:none,ppn,pph_21,pph_23,pph_4_ayat_2',
            'tax_amount'        => 'nullable|numeric|min:0',
            'document'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('document')) {
            if ($transaction->document_path) {
                Storage::disk('local')->delete($transaction->document_path);
            }
            $validated['document_path'] = $request->file('document')->store('finance/documents', 'local');
        } elseif ($request->boolean('remove_document')) {
            if ($transaction->document_path) {
                Storage::disk('local')->delete($transaction->document_path);
            }
            $validated['document_path'] = null;
        }

        $oldAccountId = $transaction->account_id;
        $oldDate = $transaction->transaction_date;
        $newAccountId = $validated['account_id'];
        $newDate = $validated['transaction_date'];

        DB::transaction(function () use ($validated, $transaction, $oldAccountId, $oldDate, $newAccountId, $newDate) {
            // Mitigate Deadlock: Lock account(s) using a consistent ascending order
            $accountsToLock = array_unique([$oldAccountId, $newAccountId]);
            sort($accountsToLock);
            FinancialAccount::whereIn('id', $accountsToLock)->orderBy('id', 'asc')->lockForUpdate()->get();

            $transaction->update($validated);

            // Proper cascade recalculation logic
            if ($oldAccountId != $newAccountId) {
                // If moved to new account, recalc BOTH old and new account histories safely
                $this->recalculateRunningBalance($oldAccountId, $oldDate);
                $this->recalculateRunningBalance($newAccountId, $newDate);
            } else {
                // Same account, calc from the earliest altered date
                $earliestDate = strtotime($oldDate) <= strtotime($newDate) ? $oldDate : $newDate;
                $this->recalculateRunningBalance($newAccountId, $earliestDate);
            }
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Delete a transaction and recalculate running balances.
     */
    public function destroy(FinancialTransaction $transaction)
    {
        $accountId = $transaction->account_id;
        $trxDate = $transaction->transaction_date;

        DB::transaction(function () use ($transaction, $accountId, $trxDate) {
            // Lock account explicitly
            FinancialAccount::where('id', $accountId)->lockForUpdate()->first();
            
            if ($transaction->document_path) {
                Storage::disk('local')->delete($transaction->document_path);
            }
            $transaction->delete();
            $this->recalculateRunningBalance($accountId, $trxDate);
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Securely download document associated with transaction.
     */
    public function downloadDocument(FinancialTransaction $transaction)
    {
        if (!$transaction->document_path) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        if (!Storage::disk('local')->exists($transaction->document_path)) {
            abort(404, 'File fisik tidak ditemukan di server.');
        }

        return response()->file(Storage::disk('local')->path($transaction->document_path));
    }

    /**
     * Recalculate running balance incrementally per account and safely handle concurrency.
     */
    private function recalculateRunningBalance(int $accountId, $startDate = null): void
    {
        // Lock already handled upstream in store/update/destroy methods to prevent cross-account deadlocks.

        // 1. Fetch the baseline balance strictly preceding the modification date
        $initialBalance = '0.00';
        
        $query = FinancialTransaction::where('account_id', $accountId);
        
        if ($startDate) {
            // Need to parse to standard format before passing logic
            if (is_numeric($startDate)) {
                $dateString = date('Y-m-d', $startDate);
            } else {
                $dateString = \Carbon\Carbon::parse($startDate)->toDateString();
            }
            
            $lastBefore = FinancialTransaction::where('account_id', $accountId)
                ->whereDate('transaction_date', '<', $dateString)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
                
            if ($lastBefore) {
                $initialBalance = (string) $lastBefore->running_balance;
            }
            
            $query->whereDate('transaction_date', '>=', $dateString);
        }

        // 2. Process only the affected subset incrementally
        $transactions = $query->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $balance = $initialBalance;
        $dirtyMonths = [];

        foreach ($transactions as $trx) {
            if ($trx->transaction_type === 'debit') {
                $balance = bcadd($balance, (string)$trx->amount, 2);
            } else {
                $balance = bcsub($balance, (string)$trx->amount, 2);
            }

            // Flag modified months
            $date = \Carbon\Carbon::parse($trx->transaction_date);
            $monthKey = $date->format('Y-m');
            $dirtyMonths[$monthKey] = true;
            
            $trx->running_balance  = $balance;
            $trx->is_end_of_month  = false; // Clear flag, we'll re-mark it cleanly below
            $trx->is_end_of_year   = false;
            $trx->saveQuietly();
        }

        // 4. Intelligently Repair EOM / EOY flags for affected months only
        foreach (array_keys($dirtyMonths) as $monthKey) {
            $year = substr($monthKey, 0, 4);
            $month = substr($monthKey, 5, 2);
            
            $endOfMonthTrx = FinancialTransaction::where('account_id', $accountId)
                ->whereYear('transaction_date', $year)
                ->whereMonth('transaction_date', $month)
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->first();
                
            if ($endOfMonthTrx) {
                $endOfMonthTrx->is_end_of_month = true;
                $endOfMonthTrx->is_end_of_year = ($month == '12');
                $endOfMonthTrx->saveQuietly();
            }
        }
    }
}
