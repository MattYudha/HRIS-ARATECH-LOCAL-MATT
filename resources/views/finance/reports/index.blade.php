@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan — ' . $periodLabel)

@push('styles')
<style>
/* ══ Reusable Finance Module Design System ══════════════ */
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

/* Period filter bar */
.period-bar {
    background:#f8f9fc; border:1.5px solid #edf0f7; border-radius:12px;
    padding:.75rem 1.1rem; margin-bottom:1.25rem;
    display:flex; flex-wrap:wrap; gap:.75rem; align-items:center;
}
.period-bar .form-select, .period-bar .form-control {
    border-radius:8px; border:1.5px solid #e4e8f0; font-size:.82rem; background:#fff;
}
.period-bar .form-select:focus, .period-bar .form-control:focus {
    border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12);
}

/* KPI Cards */
.kpi-card {
    border-radius:14px; border:none; padding:1.15rem 1.3rem;
    position:relative; overflow:hidden;
    transition:transform .2s, box-shadow .2s;
}
.kpi-card:hover { transform:translateY(-3px); box-shadow:0 10px 28px rgba(0,0,0,.1) !important; }
.kpi-card .kc-accent { position:absolute; right:1rem; top:50%; transform:translateY(-50%); font-size:2.2rem; opacity:.15; }
.kpi-card .kc-label { font-size:.65rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; margin-bottom:.3rem; }
.kpi-card .kc-value { font-size:1.35rem; font-weight:900; line-height:1.1; margin-bottom:.2rem; }
.kpi-card .kc-sub   { font-size:.7rem; opacity:.65; }
.kc-green  { background:linear-gradient(135deg,#d4f5e2,#edfbf4); }
.kc-red    { background:linear-gradient(135deg,#fde8e8,#fff5f5); }
.kc-blue   { background:linear-gradient(135deg,#dde8ff,#f0f4ff); }
.kc-purple { background:linear-gradient(135deg,#ede8ff,#f5f2ff); }

/* Section Headers */
.sec-header {
    display:flex; align-items:center; gap:.75rem;
    margin:1.5rem 0 .85rem; padding-bottom:.7rem;
    border-bottom:2px solid #f0f2f5;
}
.sec-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.sec-title { font-size:.88rem; font-weight:800; color:#344767; margin:0; }
.sec-desc  { font-size:.71rem; color:#8392ab; margin:0; }
.sec-badge { margin-left:auto; font-size:.65rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; border-radius:6px; padding:.25rem .65rem; }

/* Main content cards */
.rpt-card { border-radius:14px; border:none; overflow:hidden; transition:box-shadow .2s; height:100%; }
.rpt-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.08) !important; }
.rpt-card .card-accent { height:3px; }
.rpt-card .card-body   { padding:1.3rem 1.5rem; }

/* P&L rows */
.pl-row {
    display:flex; justify-content:space-between; align-items:center;
    padding:.5rem .4rem; border-radius:7px; transition:background .12s;
}
.pl-row:hover { background:#f7f9ff; }
.pl-row .pl-name { font-size:.82rem; color:#6b7a99; }
.pl-row .pl-val  { font-size:.82rem; font-weight:700; font-variant-numeric:tabular-nums; }
.pl-total { border-top:1.5px solid #edf0f7; margin-top:.5rem; padding-top:.5rem; }
.pl-total .pl-name { font-weight:700; color:#344767; }

/* Top list */
.top-item {
    display:flex; align-items:center; gap:.7rem;
    padding:.6rem .5rem; border-radius:9px; margin-bottom:.2rem; transition:background .12s;
}
.top-item:hover { background:#f7f9ff; }
.rank-bubble { width:26px; height:26px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; }
.r1{background:#fff3cd;color:#856404;} .r2{background:#e8e8e8;color:#555;} .r3{background:#ffe0cc;color:#9e4400;} .rx{background:#f4f6fb;color:#8392ab;}
.prog-track { height:4px; border-radius:99px; background:#f0f2f5; overflow:hidden; flex:1; }
.prog-bar   { height:4px; border-radius:99px; }

/* Net box */
.net-box { border-radius:12px; padding:1rem 1.25rem; text-align:center; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══════════════════════════════════════════════ --}}
<div class="fin-page-hero shadow">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
        <div>
            <p class="ph-title">📄 Laporan Keuangan</p>
            <p class="ph-sub">Ringkasan finansial organisasi — <strong style="color:#a8d8f0">{{ $periodLabel }}</strong></p>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <a href="{{ route('finance.charts.index', ['year' => $year]) }}" class="btn btn-sm mb-0"
                   style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
                    📈 Grafik Analitik
                </a>
                <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm mb-0"
                   style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
                    📒 Buku Kas
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
                @php $saldo = $totalDebit - $totalKredit; @endphp
                <div class="sp-val {{ $saldo < 0 ? 'text-danger' : '' }}" style="{{ $saldo >= 0 ? 'color:#a8d8f0' : '' }}">
                    {{ $saldo < 0 ? '−' : '' }}Rp {{ number_format(abs($saldo)/1e6,1) }}jt
                </div>
                <div class="sp-lbl">Saldo Bersih</div>
            </div>
            <div class="stat-pill">
                @php $np = $totalRevenue - $totalExpense; @endphp
                <div class="sp-val {{ $np < 0 ? 'text-danger' : '' }}" style="{{ $np >= 0 ? 'color:#d5c8ff' : '' }}">
                    {{ $np < 0 ? '−' : '' }}Rp {{ number_format(abs($np)/1e6,1) }}jt
                </div>
                <div class="sp-lbl">Laba / Rugi</div>
            </div>
        </div>
    </div>
</div>

{{-- ══ PERIOD FILTER ═════════════════════════════════════ --}}
<form method="GET" action="{{ route('finance.reports.index') }}">
<div class="period-bar">
    <div>
        <p class="text-xs fw-bold mb-0" style="color:#344767">🗓 Filter Periode Laporan</p>
        <p class="text-xs text-muted mb-0">Pilih tahun dan bulan untuk menyaring data laporan</p>
    </div>
    <select name="year" class="form-select" style="width:100px">
        @foreach($availableYears as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected':'' }}>{{ $y }}</option>
        @endforeach
    </select>
    <select name="month" class="form-select" style="width:155px">
        <option value="">Seluruh Tahun</option>
        @foreach(range(1,12) as $m)
            <option value="{{ $m }}" {{ $month == $m ? 'selected':'' }}>
                {{ \Carbon\Carbon::create(null,$m,1)->translatedFormat('F') }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-primary btn-sm mb-0" style="border-radius:8px;padding:.45rem 1.2rem">
        Terapkan
    </button>
    <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-secondary btn-sm mb-0" style="border-radius:8px">Reset</a>
    <span class="ms-auto badge" style="background:#eef0ff;color:#5e72e4;border-radius:7px;font-size:.72rem;padding:.35rem .75rem;font-weight:700">
        📅 {{ $periodLabel }}
    </span>
</div>
</form>

{{-- ══ KPI CARDS ══════════════════════════════════════════ --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="kpi-card kc-green shadow-sm">
            <p class="kc-label text-success">Total Masuk</p>
            <p class="kc-value text-success">Rp {{ number_format($totalDebit,0,',','.') }}</p>
            <p class="kc-sub">Semua debit / penerimaan kas</p>
            <span class="kc-accent">📥</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kc-red shadow-sm">
            <p class="kc-label text-danger">Total Keluar</p>
            <p class="kc-value text-danger">Rp {{ number_format($totalKredit,0,',','.') }}</p>
            <p class="kc-sub">Semua kredit / pengeluaran kas</p>
            <span class="kc-accent">📤</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kc-blue shadow-sm">
            <p class="kc-label" style="color:#1171ef">Saldo Bersih</p>
            <p class="kc-value {{ $saldo < 0 ? 'text-danger' : '' }}" style="{{ $saldo >= 0 ? 'color:#1171ef' : '' }}">
                {{ $saldo < 0 ? '−' : '' }}Rp {{ number_format(abs($saldo),0,',','.') }}
            </p>
            <p class="kc-sub">{{ $saldo < 0 ? '⚠️ Defisit' : '✅ Positif' }} — Debit − Kredit</p>
            <span class="kc-accent">💰</span>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="kpi-card kc-purple shadow-sm">
            <p class="kc-label" style="color:#8965e0">Laba / Rugi</p>
            <p class="kc-value {{ $np < 0 ? 'text-danger' : '' }}" style="{{ $np >= 0 ? 'color:#8965e0' : '' }}">
                {{ $np < 0 ? '−' : '' }}Rp {{ number_format(abs($np),0,',','.') }}
            </p>
            <p class="kc-sub">{{ $np < 0 ? '📉 Rugi' : '📈 Laba' }} — Revenue − Expense</p>
            <span class="kc-accent">📊</span>
        </div>
    </div>
</div>

{{-- ══ CASHFLOW CHART + PL PANEL ══════════════════════════ --}}
<div class="sec-header">
    <div class="sec-icon" style="background:#eef0ff">📊</div>
    <div>
        <p class="sec-title">Cashflow Bulanan & Laba/Rugi</p>
        <p class="sec-desc">Arus kas dan perbandingan revenue vs expense sepanjang {{ $year }}</p>
    </div>
    <span class="sec-badge" style="background:#eef0ff;color:#5e72e4">{{ $periodLabel }}</span>
</div>

<div class="row g-3 mb-3">
    {{-- Cashflow Chart --}}
    <div class="col-lg-8">
        <div class="card rpt-card shadow-sm">
            <div class="card-accent" style="background:linear-gradient(90deg,#5e72e4,#1aae6f,#f5365c)"></div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <p class="fw-bold mb-0 text-sm" style="color:#344767">Cashflow Bulanan {{ $year }}</p>
                        <p class="text-xs text-muted mb-0">Debit masuk vs Kredit keluar setiap bulan</p>
                    </div>
                    <div class="d-flex gap-2">
                        <span style="background:#e2faf0;color:#1aae6f;border-radius:6px;font-size:.68rem;font-weight:700;padding:.25rem .6rem">● Masuk</span>
                        <span style="background:#fce8e8;color:#f5365c;border-radius:6px;font-size:.68rem;font-weight:700;padding:.25rem .6rem">● Keluar</span>
                        <span style="background:#eef0ff;color:#5e72e4;border-radius:6px;font-size:.68rem;font-weight:700;padding:.25rem .6rem">— Saldo</span>
                    </div>
                </div>
                <canvas id="cashflowChart" style="max-height:270px"></canvas>
            </div>
        </div>
    </div>

    {{-- P&L Panel --}}
    <div class="col-lg-4">
        <div class="card rpt-card shadow-sm">
            <div class="card-accent" style="background:linear-gradient(90deg,#8965e0,#1aae6f)"></div>
            <div class="card-body">
                <p class="fw-bold mb-0 text-sm" style="color:#344767">Laba / Rugi — {{ $periodLabel }}</p>
                <p class="text-xs text-muted mb-3">Komparasi revenue dan expense per akun</p>

                {{-- Revenue --}}
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="background:#e2faf0;color:#1aae6f;border-radius:6px;font-size:.67rem;font-weight:800;padding:.2rem .6rem">📈 PENDAPATAN</span>
                </div>
                @forelse($revenueByAccount as $r)
                <div class="pl-row">
                    <span class="pl-name">{{ $r->account->name ?? '—' }}</span>
                    <span class="pl-val text-success">{{ number_format($r->total,0,',','.') }}</span>
                </div>
                @empty
                <p class="text-xs text-muted px-1 mb-2">Belum ada data pendapatan.</p>
                @endforelse
                <div class="pl-row pl-total mb-3">
                    <span class="pl-name">Total Revenue</span>
                    <span class="pl-val text-success">Rp {{ number_format($totalRevenue,0,',','.') }}</span>
                </div>

                {{-- Expense --}}
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span style="background:#fce8e8;color:#f5365c;border-radius:6px;font-size:.67rem;font-weight:800;padding:.2rem .6rem">📉 PENGELUARAN</span>
                </div>
                @forelse($expenseByAccount as $e)
                <div class="pl-row">
                    <span class="pl-name">{{ $e->account->name ?? '—' }}</span>
                    <span class="pl-val text-danger">{{ number_format($e->total,0,',','.') }}</span>
                </div>
                @empty
                <p class="text-xs text-muted px-1 mb-2">Belum ada data pengeluaran.</p>
                @endforelse
                <div class="pl-row pl-total mb-3">
                    <span class="pl-name">Total Expense</span>
                    <span class="pl-val text-danger">Rp {{ number_format($totalExpense,0,',','.') }}</span>
                </div>

                {{-- Net --}}
                <div class="net-box" style="background:{{ $np >= 0 ? '#e8f9ef' : '#fce8e8'}}">
                    <p class="text-xs fw-bold mb-1 {{ $np >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $np >= 0 ? '✅ LABA BERSIH' : '⚠️ RUGI BERSIH' }}
                    </p>
                    <p class="fw-bold mb-0 fs-5 {{ $np >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $np < 0 ? '−' : '' }}Rp {{ number_format(abs($np),0,',','.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══ TOP 10 ══════════════════════════════════════════════ --}}
<div class="sec-header">
    <div class="sec-icon" style="background:#fce8e8">🔴</div>
    <div>
        <p class="sec-title">Analisis Pengeluaran</p>
        <p class="sec-desc">10 transaksi & entitas penerima terbesar periode {{ $periodLabel }}</p>
    </div>
    <span class="sec-badge" style="background:#fce8e8;color:#f5365c">Top 10</span>
</div>

<div class="row g-3">
    {{-- Top 10 Transaksi --}}
    <div class="col-lg-6">
        <div class="card rpt-card shadow-sm">
            <div class="card-accent" style="background:linear-gradient(90deg,#f5365c,#fb6340)"></div>
            <div class="card-body" style="max-height:370px;overflow-y:auto">
                <p class="fw-bold text-sm mb-1" style="color:#344767">10 Pengeluaran Terbesar</p>
                <p class="text-xs text-muted mb-3">Transaksi kredit (keluar) dengan nominal tertinggi</p>
                @php $maxE = $top10Expenses->max('amount') ?: 1; @endphp
                @forelse($top10Expenses as $i => $trx)
                <div class="top-item">
                    <div class="rank-bubble {{ ['r1','r2','r3'][$i] ?? 'rx' }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="text-sm fw-semibold mb-1 text-truncate" style="color:#344767">{{ $trx->description }}</p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($trx->amount/$maxE)*100 }}%;background:linear-gradient(90deg,#f5365c,#f74f84)"></div>
                            </div>
                            <span class="text-xs text-muted text-nowrap">{{ $trx->transaction_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <span class="fw-bold text-danger text-nowrap" style="font-size:.82rem">Rp {{ number_format($trx->amount,0,',','.') }}</span>
                </div>
                @empty
                <div class="text-center py-4">
                    <div style="font-size:2rem;opacity:.2">📋</div>
                    <p class="text-xs text-muted mt-2">Tidak ada data pengeluaran pada periode ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top 10 Entitas --}}
    <div class="col-lg-6">
        <div class="card rpt-card shadow-sm">
            <div class="card-accent" style="background:linear-gradient(90deg,#fb6340,#ffd600)"></div>
            <div class="card-body" style="max-height:370px;overflow-y:auto">
                <p class="fw-bold text-sm mb-1" style="color:#344767">10 Entitas Penerima Pengeluaran Terbesar</p>
                <p class="text-xs text-muted mb-3">Vendor/entitas dengan total pembayaran terbesar pada periode ini</p>
                @php $maxEnt = $top10Entities->max('total') ?: 1; @endphp
                @forelse($top10Entities as $i => $row)
                <div class="top-item">
                    <div class="rank-bubble {{ ['r1','r2','r3'][$i] ?? 'rx' }}">{{ $i+1 }}</div>
                    <div style="flex:1;min-width:0">
                        <p class="text-sm fw-semibold mb-1 text-truncate" style="color:#344767">
                            {{ $row->receiverEntity->name ?? 'Entitas Tidak Diketahui' }}
                        </p>
                        <div class="d-flex align-items-center gap-2">
                            <div class="prog-track">
                                <div class="prog-bar" style="width:{{ ($row->total/$maxEnt)*100 }}%;background:linear-gradient(90deg,#fb6340,#ffd600)"></div>
                            </div>
                            <span class="text-xs text-muted text-nowrap">{{ $row->trx_count }} transaksi</span>
                        </div>
                    </div>
                    <span class="fw-bold text-danger text-nowrap" style="font-size:.82rem">Rp {{ number_format($row->total,0,',','.') }}</span>
                </div>
                @empty
                <div class="text-center py-4">
                    <div style="font-size:2rem;opacity:.2">🏢</div>
                    <p class="text-xs text-muted mt-2">Tidak ada data entitas pengeluaran pada periode ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const months  = @json($cashflowMonths);
const debits  = @json($cashflowDebits);
const kredits = @json($cashflowKredits);
const nets    = @json($cashflowNets);

new Chart(document.getElementById('cashflowChart'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            {
                label: 'Debit (Masuk)',
                data: debits,
                backgroundColor: 'rgba(26,174,111,.85)',
                borderRadius: 6, borderSkipped: false,
            },
            {
                label: 'Kredit (Keluar)',
                data: kredits,
                backgroundColor: 'rgba(245,54,92,.8)',
                borderRadius: 6, borderSkipped: false,
            },
            {
                label: 'Saldo Bersih',
                data: nets,
                type: 'line',
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94,114,228,.08)',
                borderWidth: 2.5, pointRadius: 4,
                fill: true, tension: 0.4,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => 'Rp ' + Math.abs(c.parsed.y).toLocaleString('id-ID') } }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: v => 'Rp ' + (v/1e6).toFixed(1) + 'jt', font: { size: 10 } },
                grid: { color: '#f0f2f5' }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
