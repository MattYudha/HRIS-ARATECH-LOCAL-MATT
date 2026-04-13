@extends('layouts.dashboard')

@section('title', 'Chart of Accounts (CoA)')

@push('styles')
<style>
.fin-page-hero {
    background: linear-gradient(135deg,#1a1f3c 0%,#2d3561 60%,#1e3a5f 100%);
    border-radius: 16px; padding: 1.4rem 1.75rem;
    margin-bottom: 1.25rem; position: relative; overflow: hidden;
}
.fin-page-hero::after {
    content:''; position:absolute; top:-40%; right:-5%; width:280px; height:280px;
    border-radius:50%; background:rgba(255,255,255,.04); pointer-events:none;
}
.fin-page-hero .ph-title { font-size:1.1rem; font-weight:800; color:#fff; margin:0; }
.fin-page-hero .ph-sub   { font-size:.75rem; color:rgba(255,255,255,.5); margin:.2rem 0 0; }

.stat-pill {
    background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.15);
    border-radius:10px; padding:.6rem 1.1rem; text-align:center; min-width:90px;
}
.stat-pill .sp-val { font-size:1.2rem; font-weight:800; color:#fff; line-height:1; }
.stat-pill .sp-lbl { font-size:.62rem; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.05em; margin-top:.2rem; }

.filter-bar {
    background:#f8f9fc; border-radius:12px; border:1.5px solid #edf0f7;
    padding:.75rem 1rem; margin-bottom:1rem;
    display:flex; flex-wrap:wrap; gap:.6rem; align-items:center;
}
.filter-bar .form-control, .filter-bar .form-select {
    border-radius:8px; border:1.5px solid #e4e8f0; font-size:.82rem; background:#fff;
}
.filter-bar .form-control:focus, .filter-bar .form-select:focus {
    border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.1);
}

/* Category tabs */
.cat-tab {
    display:inline-flex; align-items:center; gap:.35rem;
    border-radius:8px; padding:.3rem .85rem; font-size:.75rem; font-weight:700;
    text-decoration:none; border:2px solid transparent; transition:all .15s; cursor:pointer;
}
.cat-tab.active, .cat-tab:hover { filter:brightness(.92); }
.cat-asset     { background:#dff0fb; color:#1171ef; border-color:#bde0f8; }
.cat-liability { background:#fff4de; color:#d48a00; border-color:#f5dfa0; }
.cat-equity    { background:#ede8ff; color:#8965e0; border-color:#d5c8ff; }
.cat-revenue   { background:#e2faf0; color:#1aae6f; border-color:#b0f0d4; }
.cat-expense   { background:#fce8e8; color:#f5365c; border-color:#f5bfc8; }
.cat-all       { background:#f4f6fb; color:#344767; border-color:#dce0ea; }

/* COA code badge */
.coa-code {
    font-family:'Courier New',monospace; font-size:.82rem; font-weight:800;
    background:#f4f6fb; border-radius:7px; padding:.2rem .65rem;
    color:#344767; border:1.5px solid #e4e8f0; letter-spacing:.04em;
}

/* Table */
.fin-tbl { border-collapse:separate; border-spacing:0; width:100%; }
.fin-tbl thead tr { background:#f4f6fb; }
.fin-tbl thead th {
    font-size:.65rem; font-weight:800; letter-spacing:.09em; text-transform:uppercase;
    color:#8392ab; padding:.85rem 1.1rem; border-bottom:2px solid #edf0f7; white-space:nowrap;
}
.fin-tbl tbody tr { transition:background .12s; }
.fin-tbl tbody tr:hover { background:#f7f9ff; }
.fin-tbl tbody td { padding:.85rem 1.1rem; border-bottom:1px solid #f1f3f7; vertical-align:middle; }
.fin-tbl tbody tr:last-child td { border-bottom:none; }

.act-btn {
    width:30px; height:30px; border-radius:8px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.78rem; transition:all .15s; cursor:pointer; text-decoration:none;
}
.act-edit   { background:#eef0ff; color:#5e72e4; }
.act-edit:hover   { background:#5e72e4; color:#fff; }
.act-delete { background:#fce8e8; color:#f5365c; }
.act-delete:hover { background:#f5365c; color:#fff; }
</style>
@endpush

@section('content')

{{-- ── Hero ─────────────────────────────────────── --}}
@php
    use App\Models\FinancialAccount;
    $counts = FinancialAccount::groupBy('category')->selectRaw('category, count(*) as cnt')->pluck('cnt','category');
    $totalAccounts = FinancialAccount::count();
@endphp

<div class="fin-page-hero shadow">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <p class="ph-title">📊 Chart of Accounts (CoA)</p>
            <p class="ph-sub">Klasifikasi akun keuangan untuk pencatatan transaksi buku kas organisasi</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('finance.accounts.create') }}" class="btn btn-sm text-dark fw-bold mb-0"
                   style="background:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Akun
                </a>
                <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm mb-0"
                   style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
                    ↗ Input Transaksi
                </a>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="stat-pill"><div class="sp-val">{{ $totalAccounts }}</div><div class="sp-lbl">Total</div></div>
            <div class="stat-pill"><div class="sp-val" style="color:#a8d8f0">{{ $counts['asset']    ?? 0 }}</div><div class="sp-lbl">Asset</div></div>
            <div class="stat-pill"><div class="sp-val" style="color:#ffd580">{{ $counts['liability']?? 0 }}</div><div class="sp-lbl">Liability</div></div>
            <div class="stat-pill"><div class="sp-val" style="color:#d5c8ff">{{ $counts['equity']   ?? 0 }}</div><div class="sp-lbl">Equity</div></div>
            <div class="stat-pill"><div class="sp-val" style="color:#a0efcc">{{ $counts['revenue']  ?? 0 }}</div><div class="sp-lbl">Revenue</div></div>
            <div class="stat-pill"><div class="sp-val" style="color:#f5bfc8">{{ $counts['expense']  ?? 0 }}</div><div class="sp-lbl">Expense</div></div>
        </div>
    </div>
</div>

{{-- ── Alerts ───────────────────────────────────── --}}
@if(session('success'))
    <div class="alert mb-3 d-flex align-items-center gap-2 text-white py-2" style="background:#1aae6f;border-radius:10px;font-size:.84rem">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert mb-3 d-flex align-items-center gap-2 text-white py-2" style="background:#f5365c;border-radius:10px;font-size:.84rem">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

{{-- ── Category Quick Filter Tabs ───────────────── --}}
<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('finance.accounts.index') }}" class="cat-tab cat-all {{ !request('category') ? 'active' : '' }}">
        🔘 Semua ({{ $totalAccounts }})
    </a>
    @php $catConfig = ['asset'=>['🏦','Asset'],'liability'=>['💳','Liability'],'equity'=>['🏛️','Equity'],'revenue'=>['📈','Revenue'],'expense'=>['📉','Expense']]; @endphp
    @foreach($catConfig as $cat => [$icon, $label])
    <a href="{{ route('finance.accounts.index', ['category' => $cat]) }}"
       class="cat-tab cat-{{ $cat }} {{ request('category') == $cat ? 'active' : '' }}">
        {{ $icon }} {{ $label }} ({{ $counts[$cat] ?? 0 }})
    </a>
    @endforeach
</div>

{{-- ── Filter Bar ───────────────────────────────── --}}
<form method="GET" action="{{ route('finance.accounts.index') }}">
    @if(request('category'))
        <input type="hidden" name="category" value="{{ request('category') }}">
    @endif
<div class="filter-bar">
    <div class="input-group" style="width:250px">
        <span class="input-group-text" style="background:#fff;border:1.5px solid #e4e8f0;border-radius:8px 0 0 8px;border-right:0">
            <i class="bi bi-search" style="color:#8392ab;font-size:.8rem"></i>
        </span>
        <input type="text" name="search" class="form-control" style="border-left:0;border-radius:0 8px 8px 0"
               placeholder="Cari kode atau nama akun..." value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-dark btn-sm mb-0" style="border-radius:8px">Cari</button>
    @if(request('search') || request('category'))
        <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-secondary btn-sm mb-0" style="border-radius:8px">Reset</a>
    @endif
    <span class="ms-auto text-xs text-muted">{{ $accounts->total() }} akun ditemukan</span>
</div>
</form>

{{-- ── Table ────────────────────────────────────── --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">
    <div class="table-responsive">
        <table class="fin-tbl">
            <thead>
                <tr>
                    <th style="width:90px">Kode</th>
                    <th style="width:140px">Kategori</th>
                    <th>Nama Akun</th>
                    <th>Deskripsi</th>
                    <th style="width:90px;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                @php
                    $catIcons = ['asset'=>'🏦','liability'=>'💳','equity'=>'🏛️','revenue'=>'📈','expense'=>'📉'];
                @endphp
                <tr>
                    <td>
                        <span class="coa-code">{{ $account->code }}</span>
                    </td>
                    <td>
                        <span class="cat-tab cat-{{ $account->category }}" style="pointer-events:none;font-size:.68rem;padding:.2rem .65rem">
                            {{ $catIcons[$account->category] ?? '📌' }} {{ ucfirst($account->category) }}
                        </span>
                    </td>
                    <td>
                        <p class="fw-bold mb-0 text-sm" style="color:#344767">{{ $account->name }}</p>
                        <p class="text-xs text-muted mb-0">#{{ $account->id }} · {{ $account->transactions()->count() }} transaksi</p>
                    </td>
                    <td>
                        <p class="text-sm text-muted mb-0">{{ $account->description ? Str::limit($account->description, 65) : '—' }}</p>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('finance.accounts.edit', $account->id) }}" class="act-btn act-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('finance.accounts.destroy', $account->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus akun \'{{ addslashes($account->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="act-btn act-delete" title="Hapus">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="text-center py-5">
                            <div style="font-size:3rem;opacity:.15;line-height:1">📊</div>
                            <p class="fw-bold mt-3 mb-1" style="color:#344767">Belum ada akun</p>
                            <p class="text-muted text-xs mb-3">Buat Chart of Accounts terlebih dahulu sebelum mencatat transaksi.</p>
                            <a href="{{ route('finance.accounts.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px">
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
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background:#fafbff">
        <p class="text-xs text-muted mb-0">
            Menampilkan <strong>{{ $accounts->firstItem() }}–{{ $accounts->lastItem() }}</strong> dari <strong>{{ $accounts->total() }}</strong> akun
        </p>
        {{ $accounts->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
