<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialTransaction;
use App\Models\FinancialEntity;
use App\Models\FinancialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    /**
     * Halaman utama laporan keuangan:
     * - Laba/Rugi per periode
     * - Cashflow bulanan 12 bulan terakhir
     * - Top 10 pengeluaran terbesar
     * - Top 10 entitas pengeluaran terbesar
     */
    public function index(Request $request)
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', null); // null = full year

        // ── Date Range ────────────────────────────────────────────
        if ($month) {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate   = Carbon::create($year, $month, 1)->endOfMonth();
            $periodLabel = Carbon::create($year, $month, 1)->translatedFormat('F Y');
        } else {
            $startDate = Carbon::create($year, 1, 1)->startOfYear();
            $endDate   = Carbon::create($year, 12, 31)->endOfYear();
            $periodLabel = "Tahun $year";
        }

        // ── Laba / Rugi ───────────────────────────────────────────
        $totalRevenue = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'revenue'))
            ->where('transaction_type', 'debit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpense = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'expense'))
            ->where('transaction_type', 'kredit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalDebit  = FinancialTransaction::where('transaction_type', 'debit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalKredit = FinancialTransaction::where('transaction_type', 'kredit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $netBalance  = $totalDebit - $totalKredit;
        $netProfit   = $totalRevenue - $totalExpense;

        // ── Cashflow Bulanan (12 bulan) ───────────────────────────
        $cashflowMonths  = [];
        $cashflowDebits  = [];
        $cashflowKredits = [];
        $cashflowNets    = [];

        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($year, $m, 1)->startOfMonth();
            $mEnd   = Carbon::create($year, $m, 1)->endOfMonth();

            $d = FinancialTransaction::where('transaction_type', 'debit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $k = FinancialTransaction::where('transaction_type', 'kredit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');

            $cashflowMonths[]  = Carbon::create($year, $m, 1)->translatedFormat('M');
            $cashflowDebits[]  = (float) $d;
            $cashflowKredits[] = (float) $k;
            $cashflowNets[]    = (float) ($d - $k);
        }

        // ── Laba Rugi per Akun ────────────────────────────────────
        $revenueByAccount = FinancialTransaction::with('account')
            ->whereHas('account', fn($q) => $q->where('category', 'revenue'))
            ->where('transaction_type', 'debit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('account_id', DB::raw('SUM(amount) as total'))
            ->groupBy('account_id')
            ->orderByDesc('total')
            ->get();

        $expenseByAccount = FinancialTransaction::with('account')
            ->whereHas('account', fn($q) => $q->where('category', 'expense'))
            ->where('transaction_type', 'kredit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('account_id', DB::raw('SUM(amount) as total'))
            ->groupBy('account_id')
            ->orderByDesc('total')
            ->get();

        // ── Top 10 Pengeluaran Terbesar (per transaksi) ───────────
        $top10Expenses = FinancialTransaction::with(['account', 'receiverEntity'])
            ->where('transaction_type', 'kredit')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->orderByDesc('amount')
            ->limit(10)
            ->get();

        // ── Top 10 Entitas Pengeluaran Terbesar ───────────────────
        $top10Entities = FinancialTransaction::with('receiverEntity')
            ->where('transaction_type', 'kredit')
            ->whereNotNull('receiver_entity_id')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('receiver_entity_id', DB::raw('SUM(amount) as total, COUNT(*) as trx_count'))
            ->groupBy('receiver_entity_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ── Available Years ───────────────────────────────────────
        $availableYears = FinancialTransaction::selectRaw('YEAR(transaction_date) as yr')
            ->groupBy('yr')->orderBy('yr', 'desc')->pluck('yr')->toArray();
        if (empty($availableYears)) $availableYears = [now()->year];

        return view('finance.reports.index', compact(
            'year', 'month', 'periodLabel',
            'totalRevenue', 'totalExpense', 'totalDebit', 'totalKredit', 'netBalance', 'netProfit',
            'cashflowMonths', 'cashflowDebits', 'cashflowKredits', 'cashflowNets',
            'revenueByAccount', 'expenseByAccount',
            'top10Expenses', 'top10Entities',
            'availableYears', 'startDate', 'endDate'
        ));
    }

    /**
     * Halaman grafik analitik interaktif — lengkap.
     */
    public function charts(Request $request)
    {
        $year = (int) $request->get('year', now()->year);

        // ── 1 & 2. Cashflow Bulanan & Laba-Rugi per Bulan ────────
        $months = [];
        $debits = $kredits = $nets = [];
        $revenues = $expenses = $netProfits = [];

        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($year, $m, 1)->startOfMonth();
            $mEnd   = Carbon::create($year, $m, 1)->endOfMonth();

            $d = FinancialTransaction::where('transaction_type', 'debit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $k = FinancialTransaction::where('transaction_type', 'kredit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');

            $rev = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'revenue'))
                ->where('transaction_type', 'debit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $exp = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'expense'))
                ->where('transaction_type', 'kredit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');

            $months[]     = Carbon::create($year, $m, 1)->translatedFormat('M');
            $debits[]     = (float) $d;
            $kredits[]    = (float) $k;
            $nets[]       = (float) ($d - $k);
            $revenues[]   = (float) $rev;
            $expenses[]   = (float) $exp;
            $netProfits[] = (float) ($rev - $exp);
        }

        // ── 3. Cashflow Full (Kumulatif Sepanjang Waktu) ──────────
        $allTransactions = FinancialTransaction::orderBy('transaction_date')->orderBy('id')->get();
        $cumDates = $cumSaldo = [];
        $running = 0;
        foreach ($allTransactions as $trx) {
            $running += ($trx->transaction_type === 'debit') ? $trx->amount : -$trx->amount;
            $label = Carbon::parse($trx->transaction_date)->format('d/m/Y');
            // Avoid duplicate labels
            if (!empty($cumDates) && end($cumDates) === $label) {
                $cumSaldo[count($cumSaldo)-1] = (float) $running;
            } else {
                $cumDates[] = $label;
                $cumSaldo[] = (float) $running;
            }
        }

        // ── 4. Top 10 Pengeluaran Terbesar (transaksi) ───────────
        $top10Expenses = FinancialTransaction::with(['account', 'receiverEntity'])
            ->where('transaction_type', 'kredit')
            ->whereYear('transaction_date', $year)
            ->orderByDesc('amount')->limit(10)->get();

        // ── 5. Top 10 Entitas Penerima Pengeluaran ────────────────
        $top10Entities = FinancialTransaction::with('receiverEntity')
            ->where('transaction_type', 'kredit')
            ->whereNotNull('receiver_entity_id')
            ->whereYear('transaction_date', $year)
            ->select('receiver_entity_id', DB::raw('SUM(amount) as total'))
            ->groupBy('receiver_entity_id')
            ->orderByDesc('total')->limit(10)->get();

        // ── 6. Pendapatan per Akun (Revenue Breakdown) ────────────
        $revenueByAccount = FinancialTransaction::with('account')
            ->whereHas('account', fn($q) => $q->where('category', 'revenue'))
            ->where('transaction_type', 'debit')
            ->whereYear('transaction_date', $year)
            ->select('account_id', DB::raw('SUM(amount) as total'))
            ->groupBy('account_id')->orderByDesc('total')->get();

        // ── 7. Pengeluaran per Akun (Expense Breakdown) ───────────
        $expenseByAccount = FinancialTransaction::with('account')
            ->whereHas('account', fn($q) => $q->where('category', 'expense'))
            ->where('transaction_type', 'kredit')
            ->whereYear('transaction_date', $year)
            ->select('account_id', DB::raw('SUM(amount) as total'))
            ->groupBy('account_id')->orderByDesc('total')->get();

        // ── 9. Arus Utang Kartu Kredit (liability accounts) ───────
        $creditCardMonths = $months; // same 12-month labels
        $creditFlowIn = $creditFlowOut = [];
        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($year, $m, 1)->startOfMonth();
            $mEnd   = Carbon::create($year, $m, 1)->endOfMonth();

            $ci = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'liability'))
                ->where('transaction_type', 'kredit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $co = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'liability'))
                ->where('transaction_type', 'debit')
                ->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');

            $creditFlowIn[]  = (float) $ci;
            $creditFlowOut[] = (float) $co;
        }

        // ── 10. Neraca / Balance Sheet ────────────────────────────
        $balanceSheet = FinancialAccount::with(['transactions'])
            ->get()
            ->groupBy('category')
            ->map(fn($accs) => $accs->sum(fn($acc) =>
                $acc->transactions->where('transaction_type', 'debit')->sum('amount')
                - $acc->transactions->where('transaction_type', 'kredit')->sum('amount')
            ));

        $bsCategories = ['asset', 'liability', 'equity', 'revenue', 'expense'];
        $bsValues = [];
        foreach ($bsCategories as $cat) {
            $bsValues[] = (float) ($balanceSheet[$cat] ?? 0);
        }

        // ── 11. Perubahan Aset & Liabilitas per Bulan ────────────
        $assetChanges = $liabilityChanges = [];
        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($year, $m, 1)->startOfMonth();
            $mEnd   = Carbon::create($year, $m, 1)->endOfMonth();

            $ad = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'asset'))
                ->where('transaction_type', 'debit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $ak = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'asset'))
                ->where('transaction_type', 'kredit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $ld = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'liability'))
                ->where('transaction_type', 'debit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $lk = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'liability'))
                ->where('transaction_type', 'kredit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');

            $assetChanges[]     = (float) ($ad - $ak);
            $liabilityChanges[] = (float) ($lk - $ld);
        }

        // ── 12. Pertambahan/Pengurangan Kekayaan (Equity) ────────
        $equityChanges = [];
        for ($m = 1; $m <= 12; $m++) {
            $mStart = Carbon::create($year, $m, 1)->startOfMonth();
            $mEnd   = Carbon::create($year, $m, 1)->endOfMonth();

            $in  = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'equity'))
                ->where('transaction_type', 'debit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $out = FinancialTransaction::whereHas('account', fn($q) => $q->where('category', 'equity'))
                ->where('transaction_type', 'kredit')->whereBetween('transaction_date', [$mStart, $mEnd])->sum('amount');
            $equityChanges[] = (float) ($in - $out);
        }

        // ── 13. Alokasi Dana per Kategori ─────────────────────────
        $allocByCategory = FinancialTransaction::with('account')
            ->whereYear('transaction_date', $year)
            ->join('financial_accounts', 'financial_transactions.account_id', '=', 'financial_accounts.id')
            ->select('financial_accounts.category', DB::raw('SUM(financial_transactions.amount) as total'))
            ->groupBy('financial_accounts.category')
            ->orderByDesc('total')->get();

        // Available years
        $availableYears = FinancialTransaction::selectRaw('YEAR(transaction_date) as yr')
            ->groupBy('yr')->orderBy('yr', 'desc')->pluck('yr')->toArray();
        if (empty($availableYears)) $availableYears = [now()->year];

        return view('finance.charts.index', compact(
            'year', 'months',
            'debits', 'kredits', 'nets',
            'revenues', 'expenses', 'netProfits',
            'cumDates', 'cumSaldo',
            'top10Expenses', 'top10Entities',
            'revenueByAccount', 'expenseByAccount',
            'creditCardMonths', 'creditFlowIn', 'creditFlowOut',
            'bsCategories', 'bsValues',
            'assetChanges', 'liabilityChanges',
            'equityChanges',
            'allocByCategory',
            'availableYears'
        ));
    }
}
