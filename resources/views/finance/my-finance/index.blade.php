@extends('layouts.dashboard')

@section('title', 'Keuangan Saya')

@push('styles')
<style>
.my-fin-hero {
    background: linear-gradient(135deg,#172b4d 0%,#2d3561 100%);
    border-radius: 18px; padding: 1.8rem 2rem; margin-bottom: 1.5rem; color:#fff;
}
.my-fin-hero .total-label { font-size:.75rem; font-weight:800; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.08em; }
.my-fin-hero .total-val   { font-size:2.4rem; font-weight:900; line-height:1; margin:.5rem 0; color:#4ade80 !important; text-shadow: 0 0 20px rgba(74,222,128,0.3); }
.my-fin-hero .sub-val     { font-size:.85rem; color:rgba(255,255,255,.75); }

.quick-card { border-radius:15px; border:none; transition:transform .2s; }
.quick-card:hover { transform:translateY(-5px); }

.claim-dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:.4rem; }
.dot-pending  { background:#ffbb33; }
.dot-approved { background:#00c851; }
.dot-rejected { background:#ff4444; }

.table-sm-fin { font-size:.82rem; }
.table-sm-fin th { font-weight:800; color:#8392ab; text-transform:uppercase; font-size:.65rem; padding:.75rem .5rem; }
.table-sm-fin td { padding:.75rem .5rem; vertical-align:middle; border-bottom:1px solid #f1f3f7; }
</style>
@endpush

@section('content')

{{-- Hero Dashboard --}}
<div class="my-fin-hero shadow">
    <div class="row align-items-center">
        <div class="col-md-7">
            <p class="total-label">Estimasi Penghasilan Bersih (YTD {{ date('Y') }})</p>
            <h1 class="total-val">Rp {{ number_format($totalEarningsYtd, 0, ',', '.') }}</h1>
            <p class="sub-val">Total akumulasi gaji bersih yang diterima selama tahun ini.</p>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('finance.claims.create') }}" class="btn btn-sm fw-bold px-4" style="border-radius:10px; background:#ffffff; color:#172b4d; border:none; box-shadow:0 2px 8px rgba(0,0,0,0.2);">
                    <i class="bi bi-receipt me-1"></i>Ajukan Klaim
                </a>
                <a href="{{ route('finance.claims.index') }}" class="btn btn-sm fw-semibold" style="background:rgba(255,255,255,.18); color:#ffffff; border-radius:10px; border:1.5px solid rgba(255,255,255,.35);">
                    Riwayat Klaim
                </a>
            </div>
        </div>
        <div class="col-md-5 d-none d-md-block text-center">
            <div class="p-3 bg-white bg-opacity-10 rounded-4 border border-white border-opacity-10">
                <canvas id="incomeChart" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card quick-card shadow-sm h-100">
            <div class="card-body">
                <p class="text-xs fw-bold text-muted text-uppercase mb-3">Status Klaim Saya</p>
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-sm"><span class="claim-dot dot-pending"></span>Menunggu</span>
                        <span class="fw-bold">{{ $stats['total_claims_pending'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-sm"><span class="claim-dot dot-approved"></span>Disetujui (Total)</span>
                        <span class="fw-bold text-success">Rp {{ number_format($stats['total_claims_approved'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-sm"><span class="claim-dot dot-rejected"></span>Ditolak</span>
                        <span class="fw-bold text-danger">{{ $stats['total_claims_rejected'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card quick-card shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="text-xs fw-bold text-muted text-uppercase mb-0">Slip Gaji Terakhir</p>
                    <a href="{{ route('payrolls.index') }}" class="text-xs text-primary fw-bold">Semua Riwayat →</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm-fin mb-0">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Metode</th>
                                <th class="text-end">Gaji Bersih</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payrolls as $payroll)
                            <tr>
                                <td class="fw-bold">{{ $payroll->month }} {{ $payroll->year }}</td>
                                <td>{{ $payroll->payment_method }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('payrolls.show', $payroll->id) }}" class="p-1 px-2 bg-light rounded text-xs">Lihat</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted text-xs">Belum ada slip gaji tersedia.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card quick-card shadow-sm">
    <div class="card-header bg-white border-0 pb-0 d-flex justify-content-between">
        <h6 class="fw-bold mb-0">Klaim Biaya Terbaru</h6>
        <a href="{{ route('finance.claims.index') }}" class="text-xs text-primary fw-bold">Lihat Semua →</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm-fin mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th class="text-end">Nominal</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                    <tr>
                        <td class="fw-bold">{{ $claim->title }}</td>
                        <td>{{ $claim->categoryLabel() }}</td>
                        <td>{{ $claim->created_at->format('d/m/Y') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($claim->amount, 0, ',', '.') }}</td>
                        <td class="text-center">
                            <span class="claim-dot dot-{{ $claim->status }}"></span>
                            <span class="text-xs fw-bold">{{ $claim->statusLabel() }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted text-xs">Belum ada pengajuan klaim.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Gaji Bersih',
                data: @json($monthlyEarnings),
                borderColor: '#ffffff',
                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                borderWidth: 2,
                pointRadius: 0,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { display: false },
                y: { display: false, beginAtZero: true }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
