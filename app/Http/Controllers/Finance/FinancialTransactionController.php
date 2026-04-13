<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialEntity;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialTransactionController extends Controller
{
    /**
     * Display a listing of transactions (Buku Kas Ledger).
     */
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['senderEntity', 'receiverEntity', 'account', 'creator'])
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Filter by transaction type
        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        // Filter by account
        if ($request->filled('account_id')) {
            $query->where('account_id', $request->account_id);
        }

        // Search by description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(20)->withQueryString();

        // Summary totals
        $totalDebit  = FinancialTransaction::when($request->start_date, fn($q) => $q->whereDate('transaction_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('transaction_date', '<=', $request->end_date))
            ->where('transaction_type', 'debit')->sum('amount');

        $totalKredit = FinancialTransaction::when($request->start_date, fn($q) => $q->whereDate('transaction_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('transaction_date', '<=', $request->end_date))
            ->where('transaction_type', 'kredit')->sum('amount');

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
            'amount'            => 'required|numeric|min:0.01',
        ]);

        $validated['created_by'] = Auth::id();

        DB::transaction(function () use ($validated) {
            FinancialTransaction::create($validated);
            $this->recalculateRunningBalance();
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
            'amount'            => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $transaction) {
            $transaction->update($validated);
            $this->recalculateRunningBalance();
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Delete a transaction and recalculate running balances.
     */
    public function destroy(FinancialTransaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $transaction->delete();
            $this->recalculateRunningBalance();
        });

        return redirect()->route('finance.transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Recalculate running_balance for all transactions in chronological order.
     * Debit = cash in (+), Kredit = cash out (-).
     */
    private function recalculateRunningBalance(): void
    {
        $transactions = FinancialTransaction::orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $balance = 0;

        foreach ($transactions as $trx) {
            if ($trx->transaction_type === 'debit') {
                $balance += $trx->amount;
            } else {
                $balance -= $trx->amount;
            }

            // Update running balance and end-of-month/year flags
            $date = \Carbon\Carbon::parse($trx->transaction_date);
            $trx->running_balance  = $balance;
            $trx->is_end_of_month  = false;
            $trx->is_end_of_year   = false;
            $trx->saveQuietly();
        }

        // Mark end-of-month and end-of-year on the last transaction of each period
        $grouped = $transactions->groupBy(fn($t) => \Carbon\Carbon::parse($t->transaction_date)->format('Y-m'));
        foreach ($grouped as $month => $group) {
            $last = $group->last();
            $carbonDate = \Carbon\Carbon::parse($last->transaction_date);
            $last->is_end_of_month = true;
            $last->is_end_of_year  = ($carbonDate->month === 12);
            $last->saveQuietly();
        }
    }
}
