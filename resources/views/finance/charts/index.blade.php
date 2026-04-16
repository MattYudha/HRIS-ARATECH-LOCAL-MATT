@extends('layouts.dashboard')

@section('title', 'Grafik Analitik Keuangan')

@push('styles')
<style>
/* ══ Design Tokens ════════════════════════════════════════ */
:root {
    --bg:       #ffffff;
    --surface:  #f8f9fa;
    --border:   #e9ecef;
    --muted:    #6c757d;
    --body:     #212529;
    --accent:   #1a1f3c;
    --ink:      #344767;
    --radius-lg: 14px;
    --radius-md: 8px;
    --radius-sm: 5px;
    --shadow:   0 1px 4px rgba(0,0,0,.05), 0 4px 16px rgba(0,0,0,.04);
}

/* ══ Hero Banner ══════════════════════════════════════════ */
.fin-hero {
    background: var(--accent);
    border-radius: 18px;
    padding: 1.6rem 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.fin-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 280px; height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,.03);
    pointer-events: none;
}
.fin-hero .hero-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: .2rem; }
.fin-hero .hero-sub   { font-size: .78rem; color: rgba(255,255,255,.5); margin: 0; }
.fin-hero .hero-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .75rem;
    margin-top: 1.25rem;
}
.fin-hero .hero-kpi {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: var(--radius-md);
    padding: .75rem;
    text-align: center;
}
.fin-hero .hero-kpi .kpi-val {
    font-size: 1rem; font-weight: 800;
    color: #fff; line-height: 1.1;
    font-variant-numeric: tabular-nums;
}
.fin-hero .hero-kpi .kpi-val.positive { color: #10b981; }
.fin-hero .hero-kpi .kpi-val.negative { color: #f87171; }
.fin-hero .hero-kpi .kpi-lbl {
    font-size: .55rem; color: rgba(255,255,255,.5);
    text-transform: uppercase; letter-spacing: .08em; margin-top: .3rem;
    white-space: nowrap;
}

/* ══ Year Pills ═══════════════════════════════════════════ */
.year-pill {
    border-radius: 20px; padding: .3rem .9rem; font-size: .78rem;
    border: 1.5px solid rgba(255,255,255,.22);
    background: rgba(255,255,255,.07);
    color: rgba(255,255,255,.65); font-weight: 600;
    text-decoration: none; transition: all .15s; display: inline-block;
}
.year-pill:hover { background: rgba(255,255,255,.18); color: #fff; border-color: rgba(255,255,255,.45); }
.year-pill.active { background: #fff; color: var(--accent); border-color: #fff; }

/* ══ Section Header ═══════════════════════════════════════ */
.sec-header {
    display: flex; align-items: center; gap: .75rem;
    margin: 1.75rem 0 1rem;
    padding-bottom: .75rem;
    border-bottom: 1px solid var(--border);
}
.sec-icon {
    width: 32px; height: 32px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; flex-shrink: 0;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--muted);
}
.sec-title { font-size: .88rem; font-weight: 700; color: var(--body); margin: 0; }
.sec-desc  { font-size: .72rem; color: var(--muted); margin: 0; }
.sec-badge {
    margin-left: auto;
    font-size: .63rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; border-radius: var(--radius-sm);
    padding: .22rem .65rem;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--muted);
}

/* ══ Chart Cards ══════════════════════════════════════════ */
.chart-card {
    border-radius: var(--radius-lg) !important;
    border: 1px solid var(--border) !important;
    overflow: hidden;
    transition: box-shadow .2s;
    height: 100%;
    background: #fff;
    box-shadow: var(--shadow) !important;
}
.chart-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,.08) !important; }
.chart-card .card-header-accent {
    height: 3px; width: 100%;
    background: var(--accent);
}
.chart-card .card-body { padding: 1.2rem 1.4rem; }
.c-title { font-size: .85rem; font-weight: 700; color: var(--body); margin-bottom: .1rem; }
.c-sub   { font-size: .72rem; color: var(--muted); margin-bottom: .9rem; }

/* ══ KPI Mini ═════════════════════════════════════════════ */
.c-kpi-row { 
    display: grid; 
    grid-template-columns: repeat(3, 1fr); 
    gap: .65rem; 
    margin-bottom: 1.25rem; 
}
.c-kpi {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: .6rem .8rem;
    text-align: center;
}
.c-kpi .ck-v {
    font-size: .85rem; font-weight: 800;
    color: var(--body); line-height: 1.1;
    font-variant-numeric: tabular-nums;
}
.c-kpi .ck-v.positive { color: #166534; }
.c-kpi .ck-v.negative { color: #991b1b; }
.c-kpi .ck-l { font-size: .55rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; margin-top: .15rem; }

/* ══ Top Lists ════════════════════════════════════════════ */
.top-list-item {
    display: flex; align-items: center; gap: .7rem;
    padding: .55rem .4rem; border-radius: var(--radius-md);
    transition: background .1s; margin-bottom: .15rem;
}
.top-list-item:hover { background: var(--surface); }
.rank-bubble {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: .7rem; font-weight: 800;
    background: var(--surface);
    border: 1px solid var(--border);
    color: var(--muted);
}
.r1 { background: var(--accent); color: #fff; border-color: var(--accent); }
.r2 { background: #374151; color: #fff; border-color: #374151; }
.r3 { background: #6b7280; color: #fff; border-color: #6b7280; }
.rx { background: var(--surface); color: var(--muted); }
.prog-track { height: 3px; border-radius: 99px; background: var(--border); overflow: hidden; flex: 1; }
.prog-bar   { height: 3px; border-radius: 99px; background: var(--accent); }

/* ══ Empty State ══════════════════════════════════════════ */
.empty-chart {
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 3rem 1rem;
    color: var(--muted); text-align: center;
}
.empty-chart .empty-icon {
    width: 44px; height: 44px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 1.1rem; margin-bottom: .75rem; opacity: .65;
}
.empty-chart p { font-size: .78rem; margin: 0; color: var(--muted); }

/* ══ Quick Link Bar ═══════════════════════════════════════ */
.quicklinks {
    display: flex; gap: .3rem; flex-wrap: wrap;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: .55rem .9rem;
    margin-bottom: 1.25rem;
    align-items: center;
}
@media (max-width: 991px) {
    .fin-hero .hero-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .c-kpi-row { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767px) {
    .fin-hero { padding: 1.25rem; }
    .hero-title { font-size: 1.1rem; }
    .quicklinks { flex-wrap: nowrap; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
    .quicklinks::-webkit-scrollbar { display: none; }
    .chart-card .card-body { padding: 1.1rem; }
}
@media (max-width: 480px) {
    .c-kpi-row { grid-template-columns: 1fr; }
    .sec-badge { display: none; }
}
</style>
@endpush
@include('finance._finance_mobile')

@section('content')

{{-- ══════════════════════════════════════════════════ --}}
{{-- HERO BANNER                                       --}}
{{-- ══════════════════════════════════════════════════ --}}
@php
    $totalIn  = array_sum($debits);
    $totalOut = array_sum($kredits);
    $netSaldo = $totalIn - $totalOut;
    $totalAllocated = $allocByCategory->sum('total');
@endphp

<div class="fin-hero shadow">
    <div class="row w-100 g-0">
        <div class="col-12 col-xl-5 mb-3 mb-xl-0">
            <p class="hero-title">📈 Grafik Analitik Keuangan</p>
            <p class="hero-sub">Dashboard finansial lengkap organisasi — Tahun {{ $year }}</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach($availableYears as $y)
                <a href="{{ route('finance.charts.index', ['year' => $y]) }}"
                   class="year-pill {{ $year == $y ? 'active' : '' }}">{{ $y }}</a>
                @endforeach
            </div>
        </div>
        <div class="col-12 col-xl-7">
            <div class="hero-stats-grid">
                <div class="hero-kpi">
                    <div class="kpi-val positive">Rp {{ number_format($totalIn/1e6,1) }}jt</div>
                    <div class="kpi-lbl">Total Masuk</div>
                </div>
                <div class="hero-kpi">
                    <div class="kpi-val negative">Rp {{ number_format($totalOut/1e6,1) }}jt</div>
                    <div class="kpi-lbl">Total Keluar</div>
                </div>
                <div class="hero-kpi">
                    <div class="kpi-val {{ $netSaldo < 0 ? 'negative' : '' }}">
                        {{ $netSaldo < 0 ? '−' : '' }}Rp {{ number_format(abs($netSaldo)/1e6,1) }}jt
                    </div>
                    <div class="kpi-lbl">Saldo Bersih</div>
                </div>
                <div class="hero-kpi">
                    <div class="kpi-val">{{ $top10Expenses->count() }} Trx</div>
                    <div class="kpi-lbl">Top Pengeluaran</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Nav --}}
<div class="quicklinks">
    <span class="ql-label">Navigasi:</span>
    <a href="#sec-cashflow">Cashflow</a><span class="sep">·</span>
    <a href="#sec-expense">Pengeluaran</a><span class="sep">·</span>
    <a href="#sec-revexp">Pendapatan & Pengeluaran</a><span class="sep">·</span>
    <a href="#sec-balance">Neraca & Kekayaan</a><span class="sep">·</span>
    <a href="{{ route('finance.reports.index', ['year' => $year]) }}" class="ql-cta">Laporan Keuangan →</a>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 1: CASHFLOW                               --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-cashflow">
    <div class="sec-icon"><i class="bi bi-graph-up"></i></div>
    <div>
        <p class="sec-title">Cashflow</p>
        <p class="sec-desc">Arus kas masuk, keluar, dan saldo kumulatif sepanjang tahun {{ $year }}</p>
    </div>
    <span class="sec-badge">3 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 1. Laba / Rugi per Bulan --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Laba / Rugi per Bulan</p>
                        <p class="c-sub">Revenue vs Expense — mengukur profitabilitas bulanan</p>
                    </div>
                    <span style="font-size:.63rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.22rem .6rem;color:var(--muted)">P&L</span>
                </div>
                @php
                    $totalRev = array_sum($revenues);
                    $totalExp = array_sum($expenses);
                    $netP = $totalRev - $totalExp;
                @endphp
                <div class="c-kpi-row">
                    <div class="c-kpi"><div class="ck-v positive">Rp {{ number_format($totalRev/1e6,1) }}jt</div><div class="ck-l">Revenue</div></div>
                    <div class="c-kpi"><div class="ck-v negative">Rp {{ number_format($totalExp/1e6,1) }}jt</div><div class="ck-l">Expense</div></div>
                    <div class="c-kpi"><div class="ck-v {{ $netP<0?'negative':'' }}">{{ $netP<0?'−':'' }}Rp {{ number_format(abs($netP)/1e6,1) }}jt</div><div class="ck-l">{{ $netP>=0?'Laba':'Rugi' }}</div></div>
                </div>
                <canvas id="labaRugiChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 2. Cashflow Bulanan --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Cashflow Bulanan</p>
                        <p class="c-sub">Total kas masuk (debit) vs kas keluar (kredit) per bulan</p>
                    </div>
                    <span style="font-size:.63rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.22rem .6rem;color:var(--muted)">CASHFLOW</span>
                </div>
                <div class="c-kpi-row">
                    <div class="c-kpi"><div class="ck-v positive">Rp {{ number_format($totalIn/1e6,1) }}jt</div><div class="ck-l">Total Masuk</div></div>
                    <div class="c-kpi"><div class="ck-v negative">Rp {{ number_format($totalOut/1e6,1) }}jt</div><div class="ck-l">Total Keluar</div></div>
                    <div class="c-kpi"><div class="ck-v {{ $netSaldo<0?'negative':'' }}">{{ $netSaldo<0?'−':'' }}Rp {{ number_format(abs($netSaldo)/1e6,1) }}jt</div><div class="ck-l">Net Saldo</div></div>
                </div>
                <canvas id="cashflowBulananChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- 3. Cashflow Full --}}
<div class="row g-3 mb-2">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Cashflow Full — Saldo Kumulatif Sepanjang Waktu</p>
                        <p class="c-sub">Perkembangan posisi kas dari transaksi pertama sampai hari ini</p>
                    </div>
                    @if(!empty($cumSaldo))
                    <span style="font-size:.72rem;font-weight:700;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.25rem .7rem;color:var(--muted);font-variant-numeric:tabular-nums">
                        Saldo Akhir: Rp {{ number_format(end($cumSaldo),0,',','.') }}
                    </span>
                    @endif
                </div>
                <canvas id="cashflowFullChart" style="max-height:160px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 2: PENGELUARAN                            --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-expense">
    <div class="sec-icon"><i class="bi bi-arrow-up-circle"></i></div>
    <div>
        <p class="sec-title">Pengeluaran</p>
        <p class="sec-desc">10 transaksi & entitas penerima dengan nilai terbesar tahun {{ $year }}</p>
    </div>
    <span class="sec-badge">2 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 4. Top 10 Pengeluaran Terbesar --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body" style="overflow-y:auto;max-height:390px">
                <p class="c-title">10 Pengeluaran Terbesar</p>
                <p class="c-sub">Transaksi kredit (keluar) dengan nominal tertinggi tahun {{ $year }}</p>
                @php $maxE = $top10Expenses->max('amount') ?: 1; @endphp
                @forelse($top10Expenses as $i => $trx)
                <div class="top-list-item">
                    <div class="rank-bubble {{ $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : 'rx')) }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="fw-semibold mb-1 text-truncate" style="font-size:.82rem;color:var(--body)">{{ $trx->description }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($trx->amount/$maxE)*100 }}%"></div>
                            </div>
                            <span style="font-size:.7rem;color:var(--muted);white-space:nowrap">{{ $trx->transaction_date->format('d/m') }} · {{ $trx->account->code ?? '—' }}</span>
                        </div>
                    </div>
                    <span style="font-size:.82rem;font-weight:700;color:var(--body);font-variant-numeric:tabular-nums;white-space:nowrap">Rp {{ number_format($trx->amount,0,',','.') }}</span>
                </div>
                @empty
                <div class="empty-chart"><div class="empty-icon"><i class="bi bi-receipt"></i></div><p>Belum ada transaksi pengeluaran</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 5. Top 10 Entitas Penerima --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body" style="overflow-y:auto;max-height:390px">
                <p class="c-title">10 Entitas Penerima Pengeluaran Terbesar</p>
                <p class="c-sub">Vendor/entitas eksternal dengan total pembayaran terbesar</p>
                @php $maxEnt = $top10Entities->max('total') ?: 1; @endphp
                @forelse($top10Entities as $i => $row)
                <div class="top-list-item">
                    <div class="rank-bubble {{ $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : 'rx')) }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="fw-semibold mb-1 text-truncate" style="font-size:.82rem;color:var(--body)">{{ $row->receiverEntity->name ?? 'Tidak Diketahui' }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($row->total/$maxEnt)*100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <span style="font-size:.82rem;font-weight:700;color:var(--body);font-variant-numeric:tabular-nums;white-space:nowrap">Rp {{ number_format($row->total,0,',','.') }}</span>
                </div>
                @empty
                <div class="empty-chart"><div class="empty-icon"><i class="bi bi-building"></i></div><p>Belum ada data entitas pengeluaran</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 3: PENDAPATAN & PENGELUARAN               --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-revexp">
    <div class="sec-icon"><i class="bi bi-bar-chart"></i></div>
    <div>
        <p class="sec-title">Pendapatan & Pengeluaran</p>
        <p class="sec-desc">Distribusi revenue, breakdown expense, dan komparasi keduanya</p>
    </div>
    <span class="sec-badge">3 Grafik</span>
</div>

<div class="row g-3 mb-2">
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Pendapatan per Akun</p>
                <p class="c-sub">Distribusi sumber pendapatan (revenue) tahun {{ $year }}</p>
                @if($revenueByAccount->isEmpty())
                    <div class="empty-chart"><div class="empty-icon"><i class="bi bi-bar-chart-line"></i></div><p>Belum ada data pendapatan</p></div>
                @else
                <canvas id="revenueDonutChart" style="max-height:240px"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Pengeluaran per Akun</p>
                <p class="c-sub">Distribusi pengeluaran (expense) per kategori akun</p>
                @if($expenseByAccount->isEmpty())
                    <div class="empty-chart"><div class="empty-icon"><i class="bi bi-bar-chart-line"></i></div><p>Belum ada data pengeluaran</p></div>
                @else
                <canvas id="expenseDonutChart" style="max-height:240px"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Pendapatan vs Pengeluaran</p>
                <p class="c-sub">Perbandingan revenue & expense per bulan</p>
                <canvas id="revExpChart" style="max-height:240px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 4: NERACA & KEKAYAAN                      --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-balance">
    <div class="sec-icon"><i class="bi bi-bank"></i></div>
    <div>
        <p class="sec-title">Neraca & Kekayaan</p>
        <p class="sec-desc">Balance sheet, perubahan aset & liabilitas, ekuitas, dan alokasi dana</p>
    </div>
    <span class="sec-badge">5 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 9. Arus Utang KK --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Arus Utang Kartu Kredit (KK) Bulanan</p>
                <p class="c-sub">Penambahan & pelunasan akun liability (utang) per bulan</p>
                <canvas id="creditFlowChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 10. Balance Sheet --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Neraca (Balance Sheet)</p>
                <p class="c-sub">Nilai bersih transaksi per kategori akun keseluruhan</p>
                <canvas id="balanceSheetChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 11. Perubahan Aset & Liabilitas --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Perubahan Aset & Liabilitas per Bulan</p>
                <p class="c-sub">Perubahan bersih kategori aset vs liabilitas setiap bulan</p>
                <canvas id="assetLiabChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 12. Pertambahan/Pengurangan Kekayaan --}}
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <p class="c-title">Pertambahan / Pengurangan Kekayaan</p>
                <p class="c-sub">Perubahan bersih akun ekuitas (modal) per bulan tahun {{ $year }}</p>
                <canvas id="equityChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 13. Alokasi Dana --}}
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-header-accent"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Alokasi Dana per Kategori Akun</p>
                        <p class="c-sub">Distribusi total nilai transaksi ke setiap kategori akun tahun {{ $year }}</p>
                    </div>
                    <span style="font-size:.72rem;font-weight:600;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:.25rem .7rem;color:var(--muted);font-variant-numeric:tabular-nums">
                        Total: Rp {{ number_format($totalAllocated/1e6,1) }}jt
                    </span>
                </div>
                <canvas id="allocChart" style="max-height:180px"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const fmt  = v => 'Rp ' + Math.abs(v).toLocaleString('id-ID');
const fmtM = v => (Math.abs(v)/1e6).toFixed(1) + 'jt';

// Monochromatic palette — dark navy to light gray
const PALETTE = ['#1a1f3c','#374151','#6b7280','#9ca3af','#d1d5db','#e9ecef','#343a40','#495057','#6c757d','#adb5bd'];

const months  = @json($months);
const gridColor = '#f0f2f5';
const tickStyle = { font: { size: 10 }, color: '#9da7b6' };

const isMobile = window.innerWidth < 768;

const defOpts = {
    responsive: true,
    maintainAspectRatio: false,
    aspectRatio: isMobile ? 1 : 2,
    plugins: {
        legend: {
            position: 'top',
            labels: { font: { size: 10 }, boxWidth: 8, padding: 10, usePointStyle: true, color: '#6c757d' }
        },
        tooltip: { callbacks: { label: c => fmt(c.parsed.y ?? c.parsed) } }
    },
    scales: {
        y: { beginAtZero: true, ticks: { callback: fmtM, ...tickStyle }, grid: { color: gridColor } },
        x: { grid: { display: false }, ticks: tickStyle }
    }
};
const hOpts = {
    ...defOpts,
    indexAxis: 'y',
    scales: {
        x: { beginAtZero: true, ticks: { callback: fmtM, ...tickStyle }, grid: { color: gridColor } },
        y: { grid: { display: false }, ticks: tickStyle }
    }
};

// 1. Laba / Rugi
new Chart(document.getElementById('labaRugiChart'), {
    type: 'bar',
    data: { labels: months, datasets: [
        { label: 'Revenue', data: @json($revenues), backgroundColor: 'rgba(26,31,60,.85)', borderRadius: 5, borderSkipped: false },
        { label: 'Expense', data: @json($expenses), backgroundColor: 'rgba(107,114,128,.7)', borderRadius: 5, borderSkipped: false },
        { label: 'Net P&L', data: @json($netProfits), type: 'line', borderColor: '#9ca3af', backgroundColor: 'rgba(156,163,175,.06)', borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#6b7280', fill: true, tension: 0.4 }
    ]},
    options: { ...defOpts }
});

// 2. Cashflow Bulanan
new Chart(document.getElementById('cashflowBulananChart'), {
    type: 'bar',
    data: { labels: months, datasets: [
        { label: 'Debit (Masuk)',   data: @json($debits),  backgroundColor: 'rgba(26,31,60,.85)',  borderRadius: 5, borderSkipped: false },
        { label: 'Kredit (Keluar)', data: @json($kredits), backgroundColor: 'rgba(107,114,128,.65)', borderRadius: 5, borderSkipped: false },
        { label: 'Saldo Bersih',   data: @json($nets),    type: 'line', borderColor: '#adb5bd', borderDash: [5,4], backgroundColor: 'transparent', borderWidth: 2, pointRadius: 3, pointBackgroundColor: '#6b7280', fill: false, tension: 0.4 }
    ]},
    options: { ...defOpts }
});

// 3. Cashflow Full
new Chart(document.getElementById('cashflowFullChart'), {
    type: 'line',
    data: { labels: @json($cumDates), datasets: [{
        label: 'Saldo Kumulatif',
        data: @json($cumSaldo),
        borderColor: '#1a1f3c',
        backgroundColor: 'rgba(26,31,60,.07)',
        borderWidth: 2, pointRadius: 0, fill: true, tension: 0.3
    }]},
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => fmt(c.parsed.y) } } },
        scales: {
            y: { ticks: { callback: fmtM, ...tickStyle }, grid: { color: gridColor } },
            x: { grid: { display: false }, ticks: { ...tickStyle, maxTicksLimit: 14 } }
        }
    }
});

@if(!$revenueByAccount->isEmpty())
// 6. Revenue Donut
new Chart(document.getElementById('revenueDonutChart'), {
    type: 'doughnut',
    data: {
        labels: @json($revenueByAccount->map(fn($r) => $r->account->name ?? '—')->values()),
        datasets: [{ data: @json($revenueByAccount->pluck('total')), backgroundColor: PALETTE, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
    },
    options: {
        cutout: '68%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 10, padding: 8, usePointStyle: true, color: '#6c757d' } },
            tooltip: { callbacks: { label: c => c.label + ': ' + fmt(c.parsed) } }
        }
    }
});
@endif

@if(!$expenseByAccount->isEmpty())
// 7. Expense Donut
new Chart(document.getElementById('expenseDonutChart'), {
    type: 'doughnut',
    data: {
        labels: @json($expenseByAccount->map(fn($e) => $e->account->name ?? '—')->values()),
        datasets: [{ data: @json($expenseByAccount->pluck('total')), backgroundColor: PALETTE, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
    },
    options: {
        cutout: '68%',
        plugins: {
            legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 10, padding: 8, usePointStyle: true, color: '#6c757d' } },
            tooltip: { callbacks: { label: c => c.label + ': ' + fmt(c.parsed) } }
        }
    }
});
@endif

// 8. Pendapatan vs Pengeluaran
new Chart(document.getElementById('revExpChart'), {
    type: 'bar',
    data: { labels: months, datasets: [
        { label: 'Pendapatan', data: @json($revenues), backgroundColor: 'rgba(26,31,60,.85)', borderRadius: 5, borderSkipped: false },
        { label: 'Pengeluaran', data: @json($expenses), backgroundColor: 'rgba(107,114,128,.7)', borderRadius: 5, borderSkipped: false }
    ]},
    options: { ...defOpts }
});

// 9. Credit Flow
new Chart(document.getElementById('creditFlowChart'), {
    type: 'bar',
    data: { labels: months, datasets: [
        { label: 'Penambahan Utang', data: @json($creditFlowIn),  backgroundColor: 'rgba(55,65,81,.8)',   borderRadius: 5, borderSkipped: false },
        { label: 'Pelunasan Utang',  data: @json($creditFlowOut), backgroundColor: 'rgba(26,31,60,.85)', borderRadius: 5, borderSkipped: false }
    ]},
    options: { ...defOpts }
});

// 10. Balance Sheet horizontal
new Chart(document.getElementById('balanceSheetChart'), {
    type: 'bar',
    data: {
        labels: @json(array_map('ucfirst', $bsCategories)),
        datasets: [{ label: 'Nilai Bersih', data: @json($bsValues), backgroundColor: PALETTE, borderRadius: 8, borderSkipped: false }]
    },
    options: { ...hOpts, plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => fmt(c.parsed.x) } } } }
});

// 11. Aset & Liabilitas
new Chart(document.getElementById('assetLiabChart'), {
    type: 'line',
    data: { labels: months, datasets: [
        { label: 'Aset',       data: @json($assetChanges),     borderColor: '#1a1f3c', backgroundColor: 'rgba(26,31,60,.07)',  borderWidth: 2.5, pointRadius: 3, fill: true,  tension: 0.4 },
        { label: 'Liabilitas', data: @json($liabilityChanges), borderColor: '#9ca3af', backgroundColor: 'rgba(156,163,175,.05)', borderWidth: 2,   pointRadius: 3, fill: false, tension: 0.4 }
    ]},
    options: { ...defOpts }
});

// 12. Ekuitas / Kekayaan
const equityData = @json($equityChanges);
new Chart(document.getElementById('equityChart'), {
    type: 'bar',
    data: { labels: months, datasets: [{
        label: 'Perubahan Ekuitas',
        data: equityData,
        backgroundColor: equityData.map(v => v >= 0 ? 'rgba(26,31,60,.85)' : 'rgba(107,114,128,.7)'),
        borderRadius: 7, borderSkipped: false
    }]},
    options: { ...defOpts, plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => fmt(c.parsed.y) } } } }
});

// 13. Alokasi Dana
new Chart(document.getElementById('allocChart'), {
    type: 'bar',
    data: {
        labels: @json($allocByCategory->pluck('category')->map(fn($c) => ucfirst($c))->values()),
        datasets: [{ label: 'Alokasi Dana', data: @json($allocByCategory->pluck('total')->values()), backgroundColor: PALETTE, borderRadius: 9, borderSkipped: false }]
    },
    options: { ...defOpts, plugins: { legend: { display: false }, tooltip: { callbacks: { label: c => fmt(c.parsed.y) } } } }
});
</script>
@endpush
