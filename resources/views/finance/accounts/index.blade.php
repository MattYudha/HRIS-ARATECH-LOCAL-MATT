@extends('layouts.dashboard')

@section('title', 'Chart of Accounts (CoA)')

@push('styles')
<style>
/* ══ Design Tokens ═══════════════════════════════════════════════════════ */
:root {
    --bg:       #ffffff;
    --surface:  #f8f9fa;
    --border:   #e9ecef;
    --muted:    #6c757d;
    --body:     #212529;
    --accent:   #1a1f3c;
    --radius-lg: 12px;
    --radius-md: 8px;
    --radius-sm: 6px;
    --shadow:   0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
}

/* ══ Hero ════════════════════════════════════════════════════════════════ */
.coa-hero {
    background: var(--accent);
    border-radius: 20px;
    padding: 1.75rem 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.coa-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 240px; height: 240px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
    pointer-events: none;
}
.coa-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; right: 80px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(255,255,255,.025);
    pointer-events: none;
}
.hero-title {
    font-size: 1rem;
    font-weight: 700;
    color: #fff;
    margin: 0;
    letter-spacing: -.01em;
}
.hero-sub {
    font-size: .75rem;
    color: rgba(255,255,255,.45);
    margin: .2rem 0 0;
}
.hero-action {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    font-size: .78rem;
    font-weight: 600;
    padding: .38rem 1rem;
    border-radius: var(--radius-md);
    text-decoration: none;
    transition: opacity .15s;
}
.hero-action:hover { opacity: .85; }
.hero-action-primary {
    background: #fff;
    color: var(--accent);
}
.hero-action-ghost {
    background: rgba(255,255,255,.1);
    color: rgba(255,255,255,.85);
    border: 1px solid rgba(255,255,255,.18);
}

/* ══ Stat Pills ══════════════════════════════════════════════════════════ */
.stat-strip {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: .75rem;
    width: 100%;
}
.stat-pill {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: var(--radius-md);
    padding: .55rem 1rem;
    text-align: center;
    min-width: 72px;
    position: relative;
    z-index: 1;
}
.stat-pill .sp-val {
    font-size: 1.15rem;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.stat-pill .sp-lbl {
    font-size: .6rem;
    color: rgba(255,255,255,.4);
    text-transform: uppercase;
    letter-spacing: .07em;
    margin-top: .25rem;
    white-space: nowrap;
}

/* ══ Category Tabs ═══════════════════════════════════════════════════════ */
.cat-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--border);
    margin-bottom: 1rem;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    white-space: nowrap;
    scrollbar-width: none;
}
.cat-tabs::-webkit-scrollbar { display: none; }
.cat-tab-link {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    padding: .55rem 1rem;
    font-size: .78rem;
    font-weight: 600;
    color: var(--muted);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: color .15s, border-color .15s;
    white-space: nowrap;
}
.cat-tab-link:hover {
    color: var(--body);
}
.cat-tab-link.active {
    color: var(--accent);
    border-bottom-color: var(--accent);
    font-weight: 700;
}
.cat-count {
    font-size: .68rem;
    font-weight: 700;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: .05rem .45rem;
    color: var(--muted);
    line-height: 1.4;
}
.cat-tab-link.active .cat-count {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
}

/* ══ Filter Bar ══════════════════════════════════════════════════════════ */
.filter-bar {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    align-items: center;
    margin-bottom: 1.25rem;
}
.filter-bar .form-control {
    border-radius: var(--radius-md);
    border: 1.5px solid var(--border);
    font-size: .82rem;
    background: #fff;
    color: var(--body);
    transition: border-color .15s, box-shadow .15s;
}
.filter-bar .form-control:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(26,31,60,.1);
    outline: none;
}
.filter-bar .input-group-text {
    background: #fff;
    border: 1.5px solid var(--border);
    border-right: 0;
    border-radius: var(--radius-md) 0 0 var(--radius-md);
    color: var(--muted);
    font-size: .8rem;
}
.filter-bar .form-control.search-input {
    border-left: 0;
    border-radius: 0 var(--radius-md) var(--radius-md) 0;
}

/* ══ Table ═══════════════════════════════════════════════════════════════ */
.coa-table {
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
}
.coa-table thead tr {
    background: var(--surface);
}
.coa-table thead th {
    font-size: .65rem;
    font-weight: 700;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: #9da7b6;
    padding: .9rem 1.1rem;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
}
.coa-table tbody tr {
    transition: background .1s;
}
.coa-table tbody tr:hover {
    background: #fafbfc;
}
.coa-table tbody td {
    padding: .9rem 1.1rem;
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
}
.coa-table tbody tr:last-child td {
    border-bottom: none;
}

/* ══ Code Badge ══════════════════════════════════════════════════════════ */
.coa-code {
    font-family: 'Courier New', Courier, monospace;
    font-size: .8rem;
    font-weight: 700;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    padding: .22rem .65rem;
    color: var(--accent);
    letter-spacing: .05em;
}

/* ══ Category Badge ══════════════════════════════════════════════════════ */
.cat-badge {
    display: inline-flex;
    align-items: center;
    gap: .25rem;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-radius: var(--radius-sm);
    padding: .22rem .65rem;
    border: 1px solid;
}
/* Monochromatic variants — shades of dark, no rainbow */
.cat-badge-asset     { background: #f0f1f4; color: #374151; border-color: #d1d5db; }
.cat-badge-liability { background: #f0f1f4; color: #374151; border-color: #d1d5db; }
.cat-badge-equity    { background: #f0f1f4; color: #374151; border-color: #d1d5db; }
.cat-badge-revenue   { background: #f0f1f4; color: #374151; border-color: #d1d5db; }
.cat-badge-expense   { background: #f0f1f4; color: #374151; border-color: #d1d5db; }
/* Accent on active filtered category */
.cat-badge.accent {
    background: var(--accent);
    color: #fff;
    border-color: var(--accent);
}

/* ══ Action Buttons ══════════════════════════════════════════════════════ */
.act-btn {
    width: 30px; height: 30px;
    border-radius: var(--radius-md);
    border: 1px solid var(--border);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: .75rem;
    transition: all .15s;
    cursor: pointer;
    text-decoration: none;
    background: #fff;
    color: var(--muted);
}
.act-btn:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
}
.act-btn-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
}

/* ══ Alert Toast ═════════════════════════════════════════════════════════ */
.fin-alert {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .7rem 1rem;
    border-radius: var(--radius-md);
    font-size: .83rem;
    font-weight: 500;
    margin-bottom: 1rem;
    border: 1px solid;
}
.fin-alert-success {
    background: #f0faf5;
    color: #166534;
    border-color: #bbf7d0;
}
.fin-alert-error {
    background: #fef2f2;
    color: #991b1b;
    border-color: #fecaca;
}

/* ══ Empty State ═════════════════════════════════════════════════════════ */
.empty-state {
    text-align: center;
    padding: 3.5rem 1rem;
}
.empty-icon {
    width: 52px; height: 52px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 1rem;
    opacity: .7;
}
.empty-title {
    font-size: .92rem;
    font-weight: 700;
    color: var(--body);
    margin: 0 0 .3rem;
}
.empty-sub {
    font-size: .78rem;
    color: var(--muted);
    margin: 0 0 1.25rem;
    max-width: 320px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 1199px) {
    .stat-strip { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 767px) {
    .coa-hero { padding: 1.5rem; }
    .hero-title { font-size: 1.15rem; }
    .filter-bar .input-group { width: 100% !important; }
    .filter-bar .btn { flex: 1; justify-content: center; }
}
@media (max-width: 575px) {
    .stat-strip { grid-template-columns: repeat(2, 1fr); }
    .coa-hero .d-flex { flex-direction: column; align-items: stretch !important; }
    .hero-action { justify-content: center; width: 100%; }
}
</style>
@endpush
@include('finance._finance_mobile')

@section('content')

@php
    use App\Models\FinancialAccount;
    $counts = FinancialAccount::groupBy('category')
        ->selectRaw('category, count(*) as cnt')
        ->pluck('cnt', 'category');
    $totalAccounts = FinancialAccount::count();

    $catLabels = [
        'asset'     => 'Asset',
        'liability' => 'Liability',
        'equity'    => 'Equity',
        'revenue'   => 'Revenue',
        'expense'   => 'Expense',
    ];
@endphp

{{-- ══ Hero ══════════════════════════════════════════════════════════════ --}}
<div class="coa-hero">
    <div class="row align-items-center g-4">
        <div class="col-12 col-xxl-5">
            <h1 class="hero-title">Chart of Accounts (CoA)</h1>
            <p class="hero-sub">Klasifikasi akun keuangan untuk pencatatan transaksi buku kas organisasi</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a href="{{ route('finance.accounts.create') }}" class="hero-action hero-action-primary">
                    <i class="bi bi-plus-lg"></i> Tambah Akun
                </a>
                <a href="{{ route('finance.transactions.index') }}" class="hero-action hero-action-ghost">
                    <i class="bi bi-arrow-up-right"></i> Input Transaksi
                </a>
            </div>
        </div>
        <div class="col-12 col-xxl-7">
            <div class="stat-strip">
                <div class="stat-pill">
                    <div class="sp-val">{{ $totalAccounts }}</div>
                    <div class="sp-lbl">Total</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val">{{ $counts['asset'] ?? 0 }}</div>
                    <div class="sp-lbl">Asset</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val">{{ $counts['liability'] ?? 0 }}</div>
                    <div class="sp-lbl">Liability</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val">{{ $counts['equity'] ?? 0 }}</div>
                    <div class="sp-lbl">Equity</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val">{{ $counts['revenue'] ?? 0 }}</div>
                    <div class="sp-lbl">Revenue</div>
                </div>
                <div class="stat-pill">
                    <div class="sp-val">{{ $counts['expense'] ?? 0 }}</div>
                    <div class="sp-lbl">Expense</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ Alerts ══════════════════════════════════════════════════════════════ --}}
@if(session('success'))
    <div class="fin-alert fin-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="fin-alert fin-alert-error">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ session('error') }}
    </div>
@endif

{{-- ══ Category Tabs ════════════════════════════════════════════════════════ --}}
<div class="cat-tabs">
    <a href="{{ route('finance.accounts.index') }}"
       class="cat-tab-link {{ !request('category') ? 'active' : '' }}">
        Semua
        <span class="cat-count">{{ $totalAccounts }}</span>
    </a>
    @foreach($catLabels as $cat => $label)
    <a href="{{ route('finance.accounts.index', ['category' => $cat]) }}"
       class="cat-tab-link {{ request('category') == $cat ? 'active' : '' }}">
        {{ $label }}
        <span class="cat-count">{{ $counts[$cat] ?? 0 }}</span>
    </a>
    @endforeach
</div>

{{-- ══ Filter Bar ══════════════════════════════════════════════════════════ --}}
<form method="GET" action="{{ route('finance.accounts.index') }}">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
    <div class="filter-bar">
        <div class="input-group" style="width: 320px">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" name="search" class="form-control search-input"
                   placeholder="Cari kode atau nama akun..."
                   value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-sm mb-0 fw-semibold"
                style="background: var(--accent); color: #fff; border-radius: var(--radius-md); padding: .45rem 1.25rem; font-size: .8rem">
            Cari
        </button>
        @if(request('search') || request('category'))
            <a href="{{ route('finance.accounts.index') }}"
               class="btn btn-sm mb-0"
               style="border: 1.5px solid var(--border); border-radius: var(--radius-md); color: var(--muted); font-size: .8rem; padding: .45rem 1.1rem;">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
        <span class="ms-md-auto text-end" style="font-size: .75rem; color: var(--muted); min-width: 140px;">
            <strong>{{ $accounts->total() }}</strong> akun ditemukan
        </span>
    </div>
</form>

{{-- ══ Table ════════════════════════════════════════════════════════════════ --}}
<div class="card border-0" style="border-radius: 20px; overflow: hidden; box-shadow: var(--shadow);">
    <div class="table-responsive">
        <table class="coa-table">
            <thead>
                <tr>
                    <th style="width: 100px">Kode</th>
                    <th style="width: 130px">Kategori</th>
                    <th>Nama Akun</th>
                    <th class="d-none d-md-table-cell">Deskripsi</th>
                    <th style="width: 80px; text-align: center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                <tr>
                    <td>
                        <span class="coa-code">{{ $account->code }}</span>
                    </td>
                    <td>
                        <span class="cat-badge cat-badge-{{ $account->category }}">
                            {{ ucfirst($account->category) }}
                        </span>
                    </td>
                    <td>
                        <p class="fw-semibold mb-0" style="font-size: .85rem; color: var(--body)">
                            {{ $account->name }}
                        </p>
                        <p class="mb-0" style="font-size: .72rem; color: var(--muted); margin-top: .1rem">
                            {{ $account->transactions()->count() }} transaksi
                        </p>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <p class="mb-0" style="font-size: .82rem; color: var(--muted)">
                            {{ $account->description ? Str::limit($account->description, 65) : '—' }}
                        </p>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('finance.accounts.edit', $account->id) }}"
                               class="act-btn" title="Edit akun">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('finance.accounts.destroy', $account->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Hapus akun \'{{ addslashes($account->name) }}\'? Aksi ini tidak dapat dibatalkan.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn act-btn-danger" title="Hapus akun">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-journal-text"></i>
                            </div>
                            <p class="empty-title">Belum ada akun</p>
                            <p class="empty-sub">
                                Buat Chart of Accounts terlebih dahulu sebelum mencatat transaksi keuangan.
                            </p>
                            <a href="{{ route('finance.accounts.create') }}"
                               class="btn btn-sm fw-semibold"
                               style="background: var(--accent); color: #fff; border-radius: var(--radius-md); padding: .4rem 1.1rem; font-size: .8rem">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Akun Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($accounts->hasPages())
    <div class="pagination-footer">
        <p class="mb-0" style="font-size: .75rem; color: var(--muted)">
            Menampilkan <strong>{{ $accounts->firstItem() }}–{{ $accounts->lastItem() }}</strong>
            dari <strong>{{ $accounts->total() }}</strong> akun
        </p>
        {{ $accounts->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
