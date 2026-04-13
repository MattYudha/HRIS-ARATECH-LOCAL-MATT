@extends('layouts.dashboard')

@section('title', 'Grafik Analitik Keuangan')

@push('styles')
<style>
/* ══════════════════════════════════════════════════════════
   FINANCE ANALYTICS  —  Enterprise UI
   ══════════════════════════════════════════════════════════ */

/* 1. Hero Banner */
.fin-hero {
    background: linear-gradient(135deg, #1a1f3c 0%, #2d3561 60%, #1e3a5f 100%);
    border-radius: 18px;
    padding: 1.6rem 2rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
}
.fin-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.fin-hero .hero-title {
    font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: .2rem;
}
.fin-hero .hero-sub {
    font-size: .78rem; color: rgba(255,255,255,.55); margin: 0;
}
.fin-hero .hero-kpi {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 12px;
    padding: .75rem 1.25rem;
    min-width: 130px;
    text-align: center;
}
.fin-hero .hero-kpi .kpi-val { font-size: 1.15rem; font-weight: 800; color: #fff; line-height:1.1; }
.fin-hero .hero-kpi .kpi-lbl { font-size: .63rem; color: rgba(255,255,255,.5); text-transform:uppercase; letter-spacing:.06em; margin-top:.2rem; }

/* 2. Year Pills */
.year-pill {
    border-radius: 20px; padding: .3rem .9rem; font-size: .78rem;
    border: 1.5px solid rgba(255,255,255,.25); background: rgba(255,255,255,.08);
    color: rgba(255,255,255,.7); font-weight: 600; text-decoration: none;
    transition: all .15s; display: inline-block;
}
.year-pill:hover { background: rgba(255,255,255,.2); color:#fff; border-color:rgba(255,255,255,.5); }
.year-pill.active   { background: #fff; color: #1a1f3c; border-color:#fff; }

/* 3. Section Header */
.sec-header {
    display: flex; align-items: center; gap: .75rem;
    margin: 1.75rem 0 1rem;
    padding-bottom: .75rem;
    border-bottom: 2px solid #f0f2f5;
}
.sec-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.sec-title { font-size: .88rem; font-weight: 800; color: #344767; margin:0; }
.sec-desc  { font-size: .72rem; color: #8392ab; margin:0; }
.sec-badge {
    margin-left: auto;
    font-size: .65rem; font-weight: 700; letter-spacing: .07em;
    text-transform: uppercase; border-radius: 5px; padding: .25rem .65rem;
}

/* 4. Chart Cards */
.chart-card {
    border-radius: 14px; border: none; overflow: hidden;
    transition: box-shadow .2s, transform .2s;
    height: 100%;
}
.chart-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.09) !important; transform: translateY(-2px); }
.chart-card .card-header-accent {
    height: 3px; width: 100%;
}
.chart-card .card-body { padding: 1.25rem 1.4rem; }
.c-title { font-size: .85rem; font-weight: 700; color: #344767; margin-bottom: .1rem; }
.c-sub   { font-size: .71rem; color: #8392ab; margin-bottom: .9rem; }

/* 5. KPI Mini (inside chart card header) */
.c-kpi-row { display:flex; gap:.75rem; margin-bottom: .9rem; flex-wrap:wrap; }
.c-kpi {
    background: #f7f9ff; border-radius: 8px; padding: .4rem .8rem;
    flex: 1; min-width: 80px;
}
.c-kpi .ck-v { font-size: .85rem; font-weight: 800; line-height:1.1; }
.c-kpi .ck-l { font-size: .62rem; color: #8392ab; text-transform: uppercase; letter-spacing: .05em; }

/* 6. Top Lists */
.top-list-item {
    display: flex; align-items: center; gap: .7rem;
    padding: .55rem .5rem; border-radius: 9px;
    transition: background .12s; margin-bottom:.2rem;
}
.top-list-item:hover { background: #f7f9ff; }
.rank-bubble {
    width: 26px; height: 26px; border-radius: 50%; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
    font-size: .72rem; font-weight: 800;
}
.r1 { background:#fff3cd; color:#856404; }
.r2 { background:#e8e8e8; color:#555; }
.r3 { background:#ffe0cc; color:#9e4400; }
.rx { background:#f4f6fb; color:#8392ab; }
.prog-track { height:4px; border-radius:99px; background:#f0f2f5; overflow:hidden; flex:1; }
.prog-bar   { height:4px; border-radius:99px; }

/* 7. Empty State */
.empty-chart {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    padding: 3rem 1rem; color: #c0c9d8; text-align:center;
}
.empty-chart .empty-icon { font-size: 2.5rem; opacity:.35; margin-bottom: .75rem; }
.empty-chart p { font-size:.8rem; margin:0; }

/* 8. Divider */
.hr-dashed { border-top: 1.5px dashed #e8ecf4; margin: 1.25rem 0; }

/* 9. Quick link bar */
.quicklinks {
    display:flex; gap:.5rem; flex-wrap:wrap;
    background:#f7f9ff; border-radius:12px; padding:.6rem .9rem;
    margin-bottom:1.25rem; align-items:center;
}
.quicklinks a, .quicklinks span {
    font-size:.75rem; font-weight:600; color:#5e72e4;
    text-decoration:none; padding:.25rem .6rem;
    border-radius:6px; transition:background .12s;
}
.quicklinks a:hover { background:#eef0ff; }
.quicklinks .sep { color:#dee2ea; font-size:.9rem; }
</style>
@endpush

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
    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
        <div>
            <p class="hero-title">📈 Grafik Analitik Keuangan</p>
            <p class="hero-sub">Dashboard finansial lengkap organisasi — Tahun Fiskal {{ $year }}</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach($availableYears as $y)
                <a href="{{ route('finance.charts.index', ['year' => $y]) }}"
                   class="year-pill {{ $year == $y ? 'active' : '' }}">{{ $y }}</a>
                @endforeach
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <div class="hero-kpi">
                <div class="kpi-val text-success">Rp {{ number_format($totalIn/1e6,1) }}jt</div>
                <div class="kpi-lbl">Total Masuk</div>
            </div>
            <div class="hero-kpi">
                <div class="kpi-val text-danger">Rp {{ number_format($totalOut/1e6,1) }}jt</div>
                <div class="kpi-lbl">Total Keluar</div>
            </div>
            <div class="hero-kpi">
                <div class="kpi-val {{ $netSaldo >= 0 ? 'text-info' : 'text-danger' }}">
                    {{ $netSaldo < 0 ? '−' : '' }}Rp {{ number_format(abs($netSaldo)/1e6,1) }}jt
                </div>
                <div class="kpi-lbl">Saldo Bersih</div>
            </div>
            <div class="hero-kpi">
                <div class="kpi-val" style="color:#f5c842">{{ $top10Expenses->count() }} Transaksi</div>
                <div class="kpi-lbl">Top Pengeluaran</div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Nav --}}
<div class="quicklinks">
    <span>Lompat ke:</span>
    <a href="#sec-cashflow">Cashflow</a><span class="sep">·</span>
    <a href="#sec-expense">Pengeluaran</a><span class="sep">·</span>
    <a href="#sec-revexp">Pendapatan & Pengeluaran</a><span class="sep">·</span>
    <a href="#sec-balance">Neraca & Kekayaan</a><span class="sep">·</span>
    <a href="{{ route('finance.reports.index', ['year' => $year]) }}" style="color:#1aae6f">📄 Laporan Keuangan →</a>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 1: CASHFLOW                               --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-cashflow">
    <div class="sec-icon" style="background:#eef0ff">📊</div>
    <div>
        <p class="sec-title">Cashflow</p>
        <p class="sec-desc">Arus kas masuk, keluar, dan saldo kumulatif sepanjang tahun {{ $year }}</p>
    </div>
    <span class="sec-badge" style="background:#eef0ff;color:#5e72e4">3 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 1. Laba / Rugi per Bulan --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#5e72e4,#825ee4)"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Laba / Rugi per Bulan</p>
                        <p class="c-sub">Revenue vs Expense — mengukur profitabilitas bulanan</p>
                    </div>
                    <span class="badge" style="background:#eef0ff;color:#5e72e4;font-size:.65rem;border-radius:6px">P&L</span>
                </div>
                @php
                    $totalRev = array_sum($revenues);
                    $totalExp = array_sum($expenses);
                    $netP = $totalRev - $totalExp;
                @endphp
                <div class="c-kpi-row">
                    <div class="c-kpi"><div class="ck-v text-success">Rp {{ number_format($totalRev/1e6,1) }}jt</div><div class="ck-l">Revenue</div></div>
                    <div class="c-kpi"><div class="ck-v text-danger">Rp {{ number_format($totalExp/1e6,1) }}jt</div><div class="ck-l">Expense</div></div>
                    <div class="c-kpi"><div class="ck-v {{ $netP>=0?'text-primary':'text-danger' }}">{{ $netP<0?'−':'' }}Rp {{ number_format(abs($netP)/1e6,1) }}jt</div><div class="ck-l">{{ $netP>=0?'Laba':'Rugi' }}</div></div>
                </div>
                <canvas id="labaRugiChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 2. Cashflow Bulanan --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#1aae6f,#11cdef)"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Cashflow Bulanan</p>
                        <p class="c-sub">Total kas masuk (debit) vs kas keluar (kredit) per bulan</p>
                    </div>
                    <span class="badge" style="background:#e2faf0;color:#1aae6f;font-size:.65rem;border-radius:6px">CASHFLOW</span>
                </div>
                <div class="c-kpi-row">
                    <div class="c-kpi"><div class="ck-v text-success">Rp {{ number_format($totalIn/1e6,1) }}jt</div><div class="ck-l">Total Masuk</div></div>
                    <div class="c-kpi"><div class="ck-v text-danger">Rp {{ number_format($totalOut/1e6,1) }}jt</div><div class="ck-l">Total Keluar</div></div>
                    <div class="c-kpi"><div class="ck-v {{ $netSaldo>=0?'text-primary':'text-danger' }}">{{ $netSaldo<0?'−':'' }}Rp {{ number_format(abs($netSaldo)/1e6,1) }}jt</div><div class="ck-l">Net Saldo</div></div>
                </div>
                <canvas id="cashflowBulananChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- 3. Cashflow Full --}}
<div class="row g-3 mb-2">
    <div class="col-12">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#172b4d,#5e72e4,#825ee4)"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Cashflow Full — Saldo Kumulatif Sepanjang Waktu</p>
                        <p class="c-sub">Perkembangan posisi kas dari transaksi pertama sampai hari ini</p>
                    </div>
                    @if(!empty($cumSaldo))
                    <span class="badge {{ end($cumSaldo) >= 0 ? '' : '' }}" style="background:{{ end($cumSaldo)>=0?'#e2faf0':'#fce8e8' }};color:{{ end($cumSaldo)>=0?'#1aae6f':'#f5365c' }};font-size:.7rem;border-radius:6px;font-weight:700">
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
    <div class="sec-icon" style="background:#fce8e8">🔴</div>
    <div>
        <p class="sec-title">Pengeluaran</p>
        <p class="sec-desc">10 transaksi & entitas penerima dengan nilai terbesar tahun {{ $year }}</p>
    </div>
    <span class="sec-badge" style="background:#fce8e8;color:#f5365c">2 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 4. Top 10 Pengeluaran Terbesar --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#f5365c,#f74f84)"></div>
            <div class="card-body" style="overflow-y:auto;max-height:390px">
                <p class="c-title">10 Pengeluaran Terbesar</p>
                <p class="c-sub">Transaksi kredit (keluar) dengan nominal tertinggi tahun {{ $year }}</p>
                @php $maxE = $top10Expenses->max('amount') ?: 1; @endphp
                @forelse($top10Expenses as $i => $trx)
                <div class="top-list-item">
                    <div class="rank-bubble {{ ['r1','r2','r3'][$i] ?? 'rx' }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="text-sm fw-bold mb-1 text-truncate" style="color:#344767">{{ $trx->description }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($trx->amount/$maxE)*100 }}%;background:linear-gradient(90deg,#f5365c,#f74f84)"></div>
                            </div>
                            <span class="text-xs text-muted">{{ $trx->transaction_date->format('d/m') }} · {{ $trx->account->code ?? '—' }}</span>
                        </div>
                    </div>
                    <span class="fw-bold text-danger text-nowrap" style="font-size:.82rem">Rp {{ number_format($trx->amount,0,',','.') }}</span>
                </div>
                @empty
                <div class="empty-chart"><div class="empty-icon">📋</div><p>Belum ada transaksi pengeluaran</p></div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 5. Top 10 Entitas Penerima --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#fb6340,#ffd600)"></div>
            <div class="card-body" style="overflow-y:auto;max-height:390px">
                <p class="c-title">10 Entitas Penerima Pengeluaran Terbesar</p>
                <p class="c-sub">Vendor/entitas eksternal dengan total pembayaran terbesar</p>
                @php $maxEnt = $top10Entities->max('total') ?: 1; @endphp
                @forelse($top10Entities as $i => $row)
                <div class="top-list-item">
                    <div class="rank-bubble {{ ['r1','r2','r3'][$i] ?? 'rx' }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="text-sm fw-bold mb-1 text-truncate" style="color:#344767">{{ $row->receiverEntity->name ?? 'Tidak Diketahui' }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($row->total/$maxEnt)*100 }}%;background:linear-gradient(90deg,#fb6340,#ffd600)"></div>
                            </div>
                        </div>
                    </div>
                    <span class="fw-bold text-danger text-nowrap" style="font-size:.82rem">Rp {{ number_format($row->total,0,',','.') }}</span>
                </div>
                @empty
                <div class="empty-chart"><div class="empty-icon">🏢</div><p>Belum ada data entitas pengeluaran</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- SECTION 3: PENDAPATAN & PENGELUARAN               --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="sec-header" id="sec-revexp">
    <div class="sec-icon" style="background:#e2faf0">💹</div>
    <div>
        <p class="sec-title">Pendapatan & Pengeluaran</p>
        <p class="sec-desc">Distribusi revenue, breakdown expense, dan komparasi keduanya</p>
    </div>
    <span class="sec-badge" style="background:#e2faf0;color:#1aae6f">3 Grafik</span>
</div>

<div class="row g-3 mb-2">
    <div class="col-lg-4">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#1aae6f,#2dce89)"></div>
            <div class="card-body">
                <p class="c-title">Pendapatan per Akun</p>
                <p class="c-sub">Distribusi sumber pendapatan (revenue) tahun {{ $year }}</p>
                @if($revenueByAccount->isEmpty())
                    <div class="empty-chart"><div class="empty-icon">📈</div><p>Belum ada data pendapatan</p></div>
                @else
                <canvas id="revenueDonutChart" style="max-height:240px"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#f5365c,#f74f84)"></div>
            <div class="card-body">
                <p class="c-title">Pengeluaran per Akun</p>
                <p class="c-sub">Distribusi pengeluaran (expense) per kategori akun</p>
                @if($expenseByAccount->isEmpty())
                    <div class="empty-chart"><div class="empty-icon">📉</div><p>Belum ada data pengeluaran</p></div>
                @else
                <canvas id="expenseDonutChart" style="max-height:240px"></canvas>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#5e72e4,#1aae6f)"></div>
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
    <div class="sec-icon" style="background:#ede8ff">🏛️</div>
    <div>
        <p class="sec-title">Neraca & Kekayaan</p>
        <p class="sec-desc">Balance sheet, perubahan aset & liabilitas, ekuitas, dan alokasi dana</p>
    </div>
    <span class="sec-badge" style="background:#ede8ff;color:#8965e0">5 Grafik</span>
</div>

<div class="row g-3 mb-2">
    {{-- 9. Arus Utang KK --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#f5365c,#8965e0)"></div>
            <div class="card-body">
                <p class="c-title">Arus Utang Kartu Kredit (KK) Bulanan</p>
                <p class="c-sub">Penambahan & pelunasan akun liability (utang) per bulan</p>
                <canvas id="creditFlowChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 10. Balance Sheet --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#172b4d,#5e72e4)"></div>
            <div class="card-body">
                <p class="c-title">Neraca (Balance Sheet)</p>
                <p class="c-sub">Nilai bersih transaksi per kategori akun keseluruhan</p>
                <canvas id="balanceSheetChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 11. Perubahan Aset & Liabilitas --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#5e72e4,#11cdef)"></div>
            <div class="card-body">
                <p class="c-title">Perubahan Aset & Liabilitas per Bulan</p>
                <p class="c-sub">Perubahan bersih kategori aset vs liabilitas setiap bulan</p>
                <canvas id="assetLiabChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 12. Pertambahan/Pengurangan Kekayaan --}}
    <div class="col-lg-6">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#8965e0,#ffd600)"></div>
            <div class="card-body">
                <p class="c-title">Pertambahan / Pengurangan Kekayaan</p>
                <p class="c-sub">Perubahan bersih akun ekuitas (modal) per bulan tahun {{ $year }}</p>
                <canvas id="equityChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- 13. Alokasi Dana --}}
    <div class="col-12">
        <div class="card chart-card shadow-sm">
            <div class="card-header-accent" style="background:linear-gradient(90deg,#1a1f3c,#5e72e4,#1aae6f,#f5365c,#ffd600)"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <p class="c-title">Alokasi Dana per Kategori Akun</p>
                        <p class="c-sub">Distribusi total nilai transaksi ke setiap kategori akun tahun {{ $year }}</p>
                    </div>
                    <span class="badge" style="background:#f4f6fb;color:#344767;font-size:.7rem;border-radius:6px">
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
const PALETTE = ['#5e72e4','#1aae6f','#f5365c','#fb6340','#ffd600','#11cdef','#8965e0','#f3a4b5','#2dce89','#172b4d'];
const months  = @json($months);
const defOpts = {
    responsive: true, maintainAspectRatio: true,
    plugins: { legend: { position: 'top', labels: { font:{size:11}, boxWidth:11, padding:12, usePointStyle:true } }, tooltip: { callbacks: { label: c => fmt(c.parsed.y ?? c.parsed) } } },
    scales: { y: { beginAtZero: true, ticks: { callback: fmtM, font:{size:10} }, grid: { color: '#f1f3f7' } }, x: { grid: { display: false }, ticks: { font:{size:10} } } }
};
const hOpts = { ...defOpts, indexAxis:'y', scales: { x: { beginAtZero:true, ticks:{ callback: fmtM, font:{size:10} }, grid:{color:'#f1f3f7'} }, y:{ grid:{display:false}, ticks:{font:{size:11}} } } };

// 1. Laba / Rugi
new Chart(document.getElementById('labaRugiChart'), {
    type: 'bar', data: { labels: months, datasets: [
        { label:'Revenue', data:@json($revenues), backgroundColor:'rgba(26,174,111,.85)', borderRadius:5, borderSkipped:false },
        { label:'Expense', data:@json($expenses), backgroundColor:'rgba(245,54,92,.8)',   borderRadius:5, borderSkipped:false },
        { label:'Net P&L',  data:@json($netProfits), type:'line', borderColor:'#5e72e4', backgroundColor:'rgba(94,114,228,.08)', borderWidth:2.5, pointRadius:3, fill:true, tension:0.4 }
    ]}, options: { ...defOpts }
});

// 2. Cashflow Bulanan
new Chart(document.getElementById('cashflowBulananChart'), {
    type: 'bar', data: { labels: months, datasets: [
        { label:'Debit (Masuk)',  data:@json($debits),  backgroundColor:'rgba(26,174,111,.85)',  borderRadius:5, borderSkipped:false },
        { label:'Kredit (Keluar)',data:@json($kredits), backgroundColor:'rgba(245,54,92,.8)',    borderRadius:5, borderSkipped:false },
        { label:'Saldo Bersih',  data:@json($nets),    type:'line', borderColor:'#5e72e4', borderDash:[5,4], backgroundColor:'transparent', borderWidth:2, pointRadius:3, fill:false, tension:0.4 }
    ]}, options: { ...defOpts }
});

// 3. Cashflow Full
new Chart(document.getElementById('cashflowFullChart'), {
    type: 'line', data: { labels: @json($cumDates), datasets: [{
        label:'Saldo Kumulatif', data:@json($cumSaldo),
        borderColor:'#5e72e4', backgroundColor:'rgba(94,114,228,.1)',
        borderWidth:2.5, pointRadius:0, fill:true, tension:0.3
    }]},
    options: { responsive:true, maintainAspectRatio:true, plugins:{ legend:{display:false}, tooltip:{ callbacks:{label:c=>fmt(c.parsed.y)} } }, scales:{ y:{ ticks:{callback:fmtM,font:{size:10}}, grid:{color:'#f1f3f7'} }, x:{ grid:{display:false}, ticks:{font:{size:9},maxTicksLimit:14} } } }
});

@if(!$revenueByAccount->isEmpty())
// 6. Revenue Donut
new Chart(document.getElementById('revenueDonutChart'), {
    type:'doughnut', data:{ labels:@json($revenueByAccount->map(fn($r)=>$r->account->name??'—')->values()), datasets:[{ data:@json($revenueByAccount->pluck('total')), backgroundColor:PALETTE, borderWidth:2, borderColor:'#fff', hoverOffset:8 }]},
    options:{ cutout:'68%', plugins:{ legend:{position:'bottom',labels:{font:{size:10},boxWidth:10,padding:8,usePointStyle:true}}, tooltip:{callbacks:{label:c=>c.label+': '+fmt(c.parsed)}} } }
});
@endif

@if(!$expenseByAccount->isEmpty())
// 7. Expense Donut
new Chart(document.getElementById('expenseDonutChart'), {
    type:'doughnut', data:{ labels:@json($expenseByAccount->map(fn($e)=>$e->account->name??'—')->values()), datasets:[{ data:@json($expenseByAccount->pluck('total')), backgroundColor:['#f5365c','#fb6340','#ffd600','#8965e0','#5e72e4','#11cdef','#1aae6f'], borderWidth:2, borderColor:'#fff', hoverOffset:8 }]},
    options:{ cutout:'68%', plugins:{ legend:{position:'bottom',labels:{font:{size:10},boxWidth:10,padding:8,usePointStyle:true}}, tooltip:{callbacks:{label:c=>c.label+': '+fmt(c.parsed)}} } }
});
@endif

// 8. Pendapatan vs Pengeluaran
new Chart(document.getElementById('revExpChart'), {
    type:'bar', data:{ labels:months, datasets:[
        { label:'Pendapatan', data:@json($revenues), backgroundColor:'rgba(26,174,111,.85)', borderRadius:5, borderSkipped:false },
        { label:'Pengeluaran',data:@json($expenses), backgroundColor:'rgba(245,54,92,.8)',   borderRadius:5, borderSkipped:false }
    ]}, options:{ ...defOpts }
});

// 9. Credit Flow
new Chart(document.getElementById('creditFlowChart'), {
    type:'bar', data:{ labels:months, datasets:[
        { label:'Penambahan Utang', data:@json($creditFlowIn),  backgroundColor:'rgba(245,54,92,.8)',   borderRadius:5, borderSkipped:false },
        { label:'Pelunasan Utang',  data:@json($creditFlowOut), backgroundColor:'rgba(26,174,111,.85)', borderRadius:5, borderSkipped:false }
    ]}, options:{ ...defOpts }
});

// 10. Balance Sheet horizontal
new Chart(document.getElementById('balanceSheetChart'), {
    type:'bar', data:{ labels:@json(array_map('ucfirst', $bsCategories)), datasets:[{
        label:'Nilai Bersih', data:@json($bsValues),
        backgroundColor:['#5e72e4','#f5365c','#8965e0','#1aae6f','#fb6340'],
        borderRadius:8, borderSkipped:false
    }]},
    options: { ...hOpts, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>fmt(c.parsed.x)}} } }
});

// 11. Aset & Liabilitas
new Chart(document.getElementById('assetLiabChart'), {
    type:'line', data:{ labels:months, datasets:[
        { label:'Aset',       data:@json($assetChanges),     borderColor:'#5e72e4', backgroundColor:'rgba(94,114,228,.1)',  borderWidth:2.5, pointRadius:3, fill:true,  tension:0.4 },
        { label:'Liabilitas', data:@json($liabilityChanges), borderColor:'#f5365c', backgroundColor:'rgba(245,54,92,.06)', borderWidth:2.5, pointRadius:3, fill:false, tension:0.4 }
    ]}, options:{ ...defOpts }
});

// 12. Ekuitas / Kekayaan
const equityData = @json($equityChanges);
new Chart(document.getElementById('equityChart'), {
    type:'bar', data:{ labels:months, datasets:[{
        label:'Perubahan Ekuitas', data:equityData,
        backgroundColor: equityData.map(v => v >= 0 ? 'rgba(26,174,111,.85)' : 'rgba(245,54,92,.8)'),
        borderRadius:7, borderSkipped:false
    }]},
    options:{ ...defOpts, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>fmt(c.parsed.y)}} } }
});

// 13. Alokasi Dana
new Chart(document.getElementById('allocChart'), {
    type:'bar', data:{
        labels:@json($allocByCategory->pluck('category')->map(fn($c)=>ucfirst($c))->values()),
        datasets:[{ label:'Alokasi Dana', data:@json($allocByCategory->pluck('total')->values()), backgroundColor:PALETTE, borderRadius:9, borderSkipped:false }]
    }, options:{ ...defOpts, plugins:{ legend:{display:false}, tooltip:{callbacks:{label:c=>fmt(c.parsed.y)}} } }
});
</script>
@endpush
