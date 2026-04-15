@extends('layouts.dashboard')

@section('title', 'Buku Kas & Keuangan — Ledger Transaksi')

@push('styles')
<style>
/* ══ Finance Module — Transaction Ledger ════════════════ */
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
    border-radius:10px; padding:.6rem 1.1rem; text-align:center; min-width:105px;
}
.stat-pill .sp-val { font-size:1.05rem; font-weight:800; color:#fff; line-height:1; }
.stat-pill .sp-lbl { font-size:.62rem; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:.05em; margin-top:.2rem; }

/* Filter bar */
.filter-bar {
    background:#f8f9fc; border:1.5px solid #edf0f7; border-radius:12px;
    padding:.75rem 1rem; margin-bottom:1rem;
    display:flex; flex-wrap:wrap; gap:.6rem; align-items:center;
}
.filter-bar .form-control, .filter-bar .form-select {
    border-radius:8px; border:1.5px solid #e4e8f0; font-size:.82rem; background:#fff;
}
.filter-bar .form-control:focus, .filter-bar .form-select:focus {
    border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12);
}

/* Table */
.fin-tbl { border-collapse:separate; border-spacing:0; width:100%; }
.fin-tbl thead tr { background:#f4f6fb; }
.fin-tbl thead th {
    font-size:.65rem; font-weight:800; letter-spacing:.09em; text-transform:uppercase;
    color:#8392ab; padding:.85rem 1rem; border-bottom:2px solid #edf0f7; white-space:nowrap;
}
.fin-tbl tbody tr { transition:background .12s; }
.fin-tbl tbody tr:hover { background:#f7f9ff; }
.fin-tbl tbody td { padding:.85rem 1rem; border-bottom:1px solid #f1f3f7; vertical-align:middle; }
.fin-tbl tbody tr:last-child td { border-bottom:none; }

/* EOM/EOY row markers */
.eom-row td:first-child { border-left:3px solid #5e72e4; }
.eoy-row td:first-child { border-left:3px solid #1a1f3c; }
.eom-row { background:#f9faff !important; }
.eoy-row { background:#f4f4f8 !important; }

/* Type badges */
.type-debit  { background:#e2faf0; color:#1aae6f; border-radius:6px; padding:.22rem .65rem; font-size:.68rem; font-weight:800; letter-spacing:.04em; }
.type-kredit { background:#fce8e8; color:#f5365c; border-radius:6px; padding:.22rem .65rem; font-size:.68rem; font-weight:800; letter-spacing:.04em; }
.period-tag  { background:#eef0ff; color:#5e72e4; border-radius:5px; padding:.18rem .55rem; font-size:.62rem; font-weight:700; letter-spacing:.04em; }

/* Amount cells */
.amt-d  { color:#1aae6f; font-weight:800; font-size:.88rem; font-variant-numeric:tabular-nums; }
.amt-k  { color:#f5365c; font-weight:800; font-size:.88rem; font-variant-numeric:tabular-nums; }
.amt-s  { color:#344767; font-weight:800; font-size:.9rem;  font-variant-numeric:tabular-nums; }
.amt-s.neg { color:#f5365c; }

/* CoA chip */
.coa-chip {
    background:#eef0ff; color:#5e72e4; border-radius:6px;
    padding:.18rem .55rem; font-size:.7rem; font-weight:700;
    font-family:'Courier New',monospace;
}

/* Action btns */
.act-btn {
    width:28px; height:28px; border-radius:7px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.75rem; transition:all .15s; cursor:pointer; text-decoration:none;
}
.act-edit   { background:#eef0ff; color:#5e72e4; }
.act-edit:hover   { background:#5e72e4; color:#fff; }
.act-delete { background:#fce8e8; color:#f5365c; }
.act-delete:hover { background:#f5365c; color:#fff; }
.act-doc    { background:#e8f2fd; color:#1a5fb4; }
.act-doc:hover    { background:#1a5fb4; color:#fff; }
/* Tax badge */
.tax-badge {
    display:inline-block; border-radius:5px; padding:.15rem .5rem;
    font-size:.6rem; font-weight:800; letter-spacing:.04em; text-transform:uppercase;
    background:#fff4de; color:#8a5700; border:1px solid #f5dfa0;
}
</style>
@endpush
@include('finance._finance_mobile')

@section('content')

{{-- ══ HERO ══════════════ --}}
@php
    $saldo = $totalDebit - $totalKredit;
    $countTotal = \App\Models\FinancialTransaction::count();
@endphp
<div class="fin-page-hero shadow">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <p class="ph-title">📒 Buku Kas & Keuangan</p>
            <p class="ph-sub">Ledger transaksi masuk & keluar secara kronologis</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('finance.transactions.create') }}" class="btn btn-sm fw-bold mb-0"
                   style="background:#fff;color:#1a1f3c;border-radius:8px;font-size:.78rem;padding:.4rem 1rem">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Transaksi
                </a>
                <a href="{{ route('finance.reports.index') }}" class="btn btn-sm mb-0"
                   style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
                    📄 Laporan Keuangan
                </a>
                <a href="{{ route('finance.charts.index') }}" class="btn btn-sm mb-0"
                   style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
                    📈 Grafik Analitik
                </a>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="stat-pill">
                <div class="sp-val text-success">Rp {{ number_format($totalDebit/1e6,1) }}jt</div>
                <div class="sp-lbl">Total Masuk</div>
            </div>
            <div class="stat-pill">
                <div class="sp-val text-danger">Rp {{ number_format($totalKredit/1e6,1) }}jt</div>
                <div class="sp-lbl">Total Keluar</div>
            </div>
            <div class="stat-pill">
                <div class="sp-val {{ $saldo < 0 ? 'text-danger' : '' }}" style="{{ $saldo >= 0 ? 'color:#a8d8f0' : '' }}">
                    {{ $saldo < 0 ? '−' : '' }}Rp {{ number_format(abs($saldo)/1e6,1) }}jt
                </div>
                <div class="sp-lbl">Saldo Bersih</div>
            </div>
            <div class="stat-pill">
                <div class="sp-val">{{ $countTotal }}</div>
                <div class="sp-lbl">Total Transaksi</div>
            </div>
        </div>
    </div>
</div>

{{-- Alerts --}}
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

{{-- ══ FILTER BAR ════════ --}}
<form method="GET" action="{{ route('finance.transactions.index') }}">
<div class="filter-bar">
    <div class="filter-date-row">
        <input type="date" name="start_date" class="form-control form-control-sm"
               value="{{ request('start_date') }}" title="Dari tanggal">
        <span class="text-muted text-xs fw-bold">&mdash;</span>
        <input type="date" name="end_date" class="form-control form-control-sm"
               value="{{ request('end_date') }}" title="Sampai tanggal">
    </div>
    <select name="type" class="form-select form-select-sm">
        <option value="">🔄 Semua Tipe</option>
        <option value="debit"  {{ request('type')=='debit'  ?'selected':'' }}>💚 Debit (Masuk)</option>
        <option value="kredit" {{ request('type')=='kredit' ?'selected':'' }}>🔴 Kredit (Keluar)</option>
    </select>
    <select name="account_id" class="form-select form-select-sm">
        <option value="">📊 Semua Akun</option>
        @foreach($accounts as $acc)
            <option value="{{ $acc->id }}" {{ request('account_id')==$acc->id?'selected':'' }}>
                [{{ $acc->code }}] {{ $acc->name }}
            </option>
        @endforeach
    </select>
    <div class="input-group">
        <span class="input-group-text" style="background:#fff;border:1.5px solid #e4e8f0;border-radius:8px 0 0 8px;border-right:0;font-size:.8rem">
            <i class="bi bi-search" style="color:#8392ab"></i>
        </span>
        <input type="text" name="search" class="form-control form-control-sm"
               style="border-left:0;border-radius:0 8px 8px 0"
               placeholder="Keterangan..." value="{{ request('search') }}">
    </div>
    <div class="filter-actions">
        <button type="submit" class="btn btn-dark btn-sm mb-0" style="border-radius:8px">Cari</button>
        @if(request()->hasAny(['start_date','end_date','type','account_id','search']))
            <a href="{{ route('finance.transactions.index') }}" class="btn btn-outline-secondary btn-sm mb-0" style="border-radius:8px">Reset</a>
        @endif
    </div>
    <span class="ms-auto text-xs text-muted">{{ $transactions->total() }} transaksi</span>
</div>
</form>

{{-- ══ TABLE ═════════════ --}}
<div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">
    <div class="table-responsive">
        <table class="fin-tbl">
            <thead>
                <tr>
                    <th style="width:110px">Tanggal</th>
                    <th>Keterangan</th>
                    <th class="d-none d-md-table-cell" style="width:140px">Akun</th>
                    <th class="d-none d-lg-table-cell">Entitas</th>
                    <th class="text-end" style="width:130px">Debit</th>
                    <th class="text-end" style="width:130px">Kredit</th>
                    <th class="d-none d-md-table-cell text-end" style="width:135px">Saldo Running</th>
                    <th style="width:75px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr class="{{ $trx->is_end_of_year ? 'eoy-row' : ($trx->is_end_of_month ? 'eom-row' : '') }}">
                    <td>
                        <p class="fw-bold mb-1 text-sm" style="color:#344767">
                            {{ $trx->transaction_date->format('d M Y') }}
                        </p>
                        <div class="d-flex flex-wrap gap-1">
                            <span class="type-{{ $trx->transaction_type }}">
                                {{ strtoupper($trx->transaction_type) }}
                            </span>
                            @if($trx->is_end_of_month)
                                <span class="period-tag">AKHIR BLN</span>
                            @endif
                            @if($trx->is_end_of_year)
                                <span class="period-tag" style="background:#e8e8f0;color:#172b4d">AKHIR THN</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <p class="fw-semibold text-sm mb-0" style="color:#344767">{{ $trx->description }}</p>
                        <p class="text-xs text-muted mb-0">oleh {{ $trx->creator->name ?? 'System' }}</p>
                        @if($trx->tax_type && $trx->tax_type !== 'none')
                            @php $taxLabels = ['ppn'=>'PPN','pph_21'=>'PPh 21','pph_23'=>'PPh 23','pph_4_ayat_2'=>'PPh 4(2)']; @endphp
                            <span class="tax-badge mt-1">{{ $taxLabels[$trx->tax_type] ?? $trx->tax_type }} Rp {{ number_format($trx->tax_amount,0,',','.') }}</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span class="coa-chip">{{ $trx->account->code ?? '—' }}</span>
                        <p class="text-xs text-muted mb-0 mt-1">{{ $trx->account->name ?? '—' }}</p>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <div style="font-size:.78rem;line-height:1.6;color:#344767">
                            @if($trx->senderEntity)
                                <span class="text-muted">Dari:</span> <strong>{{ $trx->senderEntity->name }}</strong><br>
                            @endif
                            @if($trx->receiverEntity)
                                <span class="text-muted">Ke:</span> <strong>{{ $trx->receiverEntity->name }}</strong>
                            @endif
                            @if(!$trx->senderEntity && !$trx->receiverEntity)
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-end">
                        @if($trx->transaction_type === 'debit')
                            <span class="amt-d">{{ number_format($trx->amount,0,',','.') }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($trx->transaction_type === 'kredit')
                            <span class="amt-k">{{ number_format($trx->amount,0,',','.') }}</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="d-none d-md-table-cell text-end">
                        <span class="amt-s {{ $trx->running_balance < 0 ? 'neg' : '' }}">
                            {{ $trx->running_balance < 0 ? '−' : '' }}{{ number_format(abs($trx->running_balance),0,',','.') }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1 justify-content-end">
                            @if($trx->document_path)
                            <a href="{{ route('finance.transactions.document', $trx->id) }}" target="_blank"
                               class="act-btn act-doc" title="Unduh Dokumen">
                                <i class="bi bi-paperclip"></i>
                            </a>
                            @endif
                            <a href="{{ route('finance.transactions.edit', $trx->id) }}" class="act-btn act-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form action="{{ route('finance.transactions.destroy', $trx->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus transaksi ini? Saldo akan dihitung ulang.')">
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
                    <td colspan="8">
                        <div class="text-center py-5">
                            <div style="font-size:3rem;opacity:.15;line-height:1">📒</div>
                            <p class="fw-bold mt-3 mb-1" style="color:#344767">Belum ada transaksi</p>
                            <p class="text-muted text-xs mb-3">Mulai catat pemasukan dan pengeluaran kas organisasi Anda.</p>
                            <a href="{{ route('finance.transactions.create') }}" class="btn btn-primary btn-sm" style="border-radius:8px">
                                <i class="bi bi-plus-lg me-1"></i>Tambah Transaksi Pertama
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transactions->hasPages())
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="background:#fafbff">
        <p class="text-xs text-muted mb-0">
            Menampilkan <strong>{{ $transactions->firstItem() }}–{{ $transactions->lastItem() }}</strong> dari <strong>{{ $transactions->total() }}</strong> transaksi
        </p>
        {{ $transactions->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
