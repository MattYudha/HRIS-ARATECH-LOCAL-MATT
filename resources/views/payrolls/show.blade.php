@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3><i class="bi bi-receipt"></i> Slip Gaji</h3>
                <p class="text-subtitle text-muted">{{ $payroll->employee?->fullname ?? 'Unknown' }} — {{ $payroll->period_label }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payroll</a></li>
                        <li class="breadcrumb-item active">Slip Gaji</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mb-3 no-print">
        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        <button id="btn-print" class="btn btn-success btn-sm"><i class="bi bi-printer"></i> Cetak Slip</button>
        @if(in_array(session('role'), ['Super Admin', 'HR Administrator', 'Super Admin']))
            <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        @endif
        <span class="ms-auto">{!! $payroll->status_badge !!}</span>
    </div>

    <div class="card shadow-sm" id="print-area">
        <div class="card-body p-4 payslip-container">

            {{-- Company Header --}}
            <div class="text-center mb-2 payslip-header">
                <h4 class="mb-0 fw-bold text-white" style="font-size: 1.15rem;">PT. Aratech Nusantara Indonesia</h4>
                <p class="text-gray-300 mb-0" style="font-size: 0.78rem;">Jl. Jend. Sudirman No. 55, Jakarta Pusat</p>
                <hr class="my-1 border-gray-600">
                <h5 class="text-primary fw-bold mb-0" style="font-size: 1rem;">SLIP GAJI KARYAWAN</h5>
                <p class="text-gray-300 mb-0" style="font-size: 0.82rem;">Periode: {{ $payroll->period_label }}</p>
            </div>

            {{-- Employee Info --}}
            <div class="row mb-2" style="font-size: 0.82rem;">
                <div class="col-6">
                    <table class="table table-borderless table-sm mb-0 slip-info-table">
                        <tr>
                            <td class="fw-bold text-gray-300" style="width:120px; padding: 2px 4px;">Nama</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ $payroll->employee?->fullname ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-gray-300" style="padding: 2px 4px;">NIK</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ $payroll->employee?->emp_code ?? $payroll->employee?->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-gray-300" style="padding: 2px 4px;">Departemen</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ $payroll->employee?->department?->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-borderless table-sm mb-0 slip-info-table">
                        <tr>
                            <td class="fw-bold text-gray-300" style="width:120px; padding: 2px 4px;">Jabatan</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ $payroll->employee?->employeePositions?->where('is_active', true)->first()?->position?->position_name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-gray-300" style="padding: 2px 4px;">Tanggal Bayar</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ $payroll->pay_date?->format('d F Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-gray-300" style="padding: 2px 4px;">Status</td>
                            <td class="text-white" style="padding: 2px 4px;">: {{ ucfirst($payroll->status ?? 'draft') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row g-2">
                {{-- Pendapatan --}}
                <div class="col-6">
                    <div class="border rounded p-2 h-100 payslip-dark-success">
                        <h6 class="fw-bold text-green-400 border-bottom border-green-600 pb-1 mb-1" style="font-size: 0.85rem;"><i class="bi bi-wallet2 text-green-400"></i> PENDAPATAN</h6>
                        <table class="table table-sm table-borderless mb-0 slip-table">
                            <tr>
                                <td class="text-gray-300">Gaji Pokok</td>
                                <td class="text-end fw-bold text-white">Rp {{ number_format($payroll->salary, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->transport_allowance > 0)
                            <tr>
                                <td class="text-gray-300">Tunjangan Transport</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->transport_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->meal_allowance > 0)
                            <tr>
                                <td class="text-gray-300">Tunjangan Makan</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->position_allowance > 0)
                            <tr>
                                <td class="text-gray-300">Tunjangan Jabatan</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->position_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->overtime_amount > 0)
                            <tr>
                                <td class="text-gray-300">Lembur ({{ $payroll->overtime_hours }} jam)</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->overtime_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->performance_bonus > 0)
                            <tr>
                                <td class="text-gray-300">Bonus Kinerja</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->performance_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->attendance_bonus > 0)
                            <tr>
                                <td class="text-gray-300">Bonus Kehadiran</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->attendance_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_bonus > 0)
                            <tr>
                                <td class="text-gray-300">Bonus Lainnya</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->other_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->bonus_notes)
                            <tr>
                                <td colspan="2" class="text-gray-400" style="font-size: 0.72rem; padding-top:0;"><em>{{ $payroll->bonus_notes }}</em></td>
                            </tr>
                            @endif
                            <tr class="border-top border-green-600">
                                <td class="fw-bold text-green-400">Total Pendapatan</td>
                                <td class="text-end fw-bold text-green-400">Rp {{ number_format($payroll->total_earnings, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Potongan --}}
                <div class="col-6">
                    <div class="border rounded p-2 h-100 payslip-dark-danger">
                        <h6 class="fw-bold text-red-400 border-bottom border-red-600 pb-1 mb-1" style="font-size: 0.85rem;"><i class="bi bi-scissors text-red-400"></i> POTONGAN</h6>
                        <table class="table table-sm table-borderless mb-0 slip-table">
                            @if($payroll->late_deduction > 0)
                            <tr>
                                <td class="text-gray-300">Pot. Telat ({{ $payroll->late_count }}×)</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->absent_deduction > 0)
                            <tr>
                                <td class="text-gray-300">Pot. Absen ({{ $payroll->absent_count }} hari)</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->absent_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->penalty_amount > 0)
                            <tr>
                                <td class="text-gray-300">Denda/Penalti</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->penalty_amount, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->penalty_notes)
                            <tr>
                                <td colspan="2" class="text-gray-400" style="font-size: 0.72rem; padding-top:0;"><em>{{ $payroll->penalty_notes }}</em></td>
                            </tr>
                            @endif
                            @endif
                            @if($payroll->bpjs_kes > 0)
                            <tr>
                                <td class="text-gray-300">BPJS Kesehatan</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->bpjs_kes, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->bpjs_tk > 0)
                            <tr>
                                <td class="text-gray-300">BPJS Ketenagakerjaan</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->bpjs_tk, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->pph21 > 0)
                            <tr>
                                <td class="text-gray-300">PPh 21</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_deduction > 0)
                            <tr>
                                <td class="text-gray-300">Potongan Lain</td>
                                <td class="text-end text-white">Rp {{ number_format($payroll->other_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->deduction_notes)
                            <tr>
                                <td colspan="2" class="text-gray-400" style="font-size: 0.72rem; padding-top:0;"><em>{{ $payroll->deduction_notes }}</em></td>
                            </tr>
                            @endif
                            @endif
                            @if($payroll->total_deductions == 0)
                            <tr>
                                <td class="text-gray-400" colspan="2"><em>Tidak ada potongan</em></td>
                            </tr>
                            @endif
                            <tr class="border-top border-red-600">
                                <td class="fw-bold text-red-400">Total Potongan</td>
                                <td class="text-end fw-bold text-red-400">Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Kehadiran Info --}}
            @if($payroll->working_days > 0)
            <div class="mt-2">
                <div class="border rounded py-1 px-2 payslip-dark-info" style="font-size: 0.78rem;">
                    <i class="bi bi-calendar-check text-blue-400"></i>
                    <strong class="text-gray-300">Kehadiran:</strong>
                    Hari Kerja: <strong class="text-white">{{ $payroll->working_days }}</strong> |
                    Hadir: <strong class="text-white">{{ $payroll->days_present }}</strong> |
                    Telat: <strong class="text-white">{{ $payroll->late_count }}</strong> |
                    Absen: <strong class="text-white">{{ $payroll->absent_count }}</strong>
                </div>
            </div>
            @endif

            {{-- Summary Box --}}
            <div class="row mt-2">
                <div class="col-6 offset-6">
                    <div class="border rounded p-2 payslip-dark-primary">
                        <table class="table table-sm table-borderless mb-0 slip-table">
                            <tr>
                                <td class="fw-bold text-gray-300">Total Pendapatan</td>
                                <td class="text-end text-green-400 fw-bold">Rp {{ number_format($payroll->total_earnings, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-gray-300">Total Potongan</td>
                                <td class="text-end text-red-400 fw-bold">- Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="border-top border-2 border-blue-500">
                                <td class="fw-bold text-white" style="font-size: 1.05rem;">GAJI BERSIH</td>
                                <td class="text-end fw-bold text-cyan-400" style="font-size: 1.05rem;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            @if($payroll->notes)
            <div class="mt-2">
                <p class="text-gray-400 mb-0" style="font-size: 0.78rem;"><strong class="text-gray-300">Catatan:</strong> {{ $payroll->notes }}</p>
            </div>
            @endif

            {{-- Signature Lines --}}
            <div class="row text-center signature-area">
                <div class="col-4">
                    <p class="mb-0 text-gray-300">Diterima oleh,</p>
                    <div class="signature-space"></div>
                    <p class="border-top d-inline-block mb-0" style="min-width: 140px;">
                        <strong class="text-white">{{ $payroll->employee?->fullname ?? '________________' }}</strong><br>
                        <small class="text-gray-400">Karyawan</small>
                    </p>
                </div>
                <div class="col-4"></div>
                <div class="col-4">
                    <p class="mb-0 text-gray-300">Disetujui oleh,</p>
                    <div class="signature-space"></div>
                    <p class="border-top d-inline-block mb-0" style="min-width: 140px;">
                        <strong class="text-white">________________</strong><br>
                        <small class="text-gray-400">HR Administrator Manager / Unit Head</small>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
  
  /* Ubah semua text putih jadi hitam */
.text-white {
    color: #000 !important;
}

/* Untuk dark mode juga dipaksa hitam */
[data-bs-theme="dark"] .text-white {
    color: #000 !important;
}
  /* Ubah semua teks abu-abu jadi hitam */
.text-gray-300,
.text-gray-400,
.text-muted {
    color: #000 !important;
}

/* Untuk dark mode juga dipaksa hitam */
[data-bs-theme="dark"] .text-gray-300,
[data-bs-theme="dark"] .text-gray-400,
[data-bs-theme="dark"] .text-muted {
    color: #000 !important;
}
/* Screen styles */
.slip-table td { font-size: 0.82rem; padding: 2px 4px !important; }
.signature-area { margin-top: 30px; font-size: 0.82rem; }
.signature-space { height: 60px; }

/* Dark mode payslip styling */
[data-bs-theme="dark"] .payslip-container {
    background-color: var(--bs-gray-800) !important;
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .payslip-container .card {
    background-color: var(--bs-gray-800) !important;
    border-color: var(--bs-gray-600) !important;
}

[data-bs-theme="dark"] .payslip-container .border.rounded {
    background-color: var(--bs-gray-800) !important;
    border-color: var(--bs-gray-600) !important;
}

[data-bs-theme="dark"] .payslip-container .payslip-dark-success {
    background-color: #1a2e1a !important;
    border-color: #2d5a2d !important;
}

[data-bs-theme="dark"] .payslip-container .payslip-dark-danger {
    background-color: #2e1a1a !important;
    border-color: #5a2d2d !important;
}

[data-bs-theme="dark"] .payslip-container .payslip-dark-primary {
    background-color: #1a2e4a !important;
    border-color: #2d4a5a !important;
}

[data-bs-theme="dark"] .payslip-container .payslip-dark-info {
    background-color: #2d3748 !important;
    border-color: #4a5568 !important;
}

[data-bs-theme="dark"] .payslip-container .text-muted {
    color: var(--bs-gray-400) !important;
}

[data-bs-theme="dark"] .payslip-container h4,
[data-bs-theme="dark"] .payslip-container h5,
[data-bs-theme="dark"] .payslip-container h6 {
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .payslip-container .slip-info-table td {
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .payslip-container .slip-table td {
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .payslip-container .badge {
    color: var(--bs-gray-900) !important;
}

[data-bs-theme="dark"] .payslip-container .badge.bg-primary {
    background-color: var(--bs-primary) !important;
}

[data-bs-theme="dark"] .payslip-container .badge.bg-success {
    background-color: var(--bs-success) !important;
}

[data-bs-theme="dark"] .payslip-container .badge.bg-warning {
    background-color: var(--bs-warning) !important;
    color: var(--bs-gray-900) !important;
}

/* Dark mode button styling */
[data-bs-theme="dark"] .btn-secondary {
    background-color: var(--bs-gray-600) !important;
    border-color: var(--bs-gray-500) !important;
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .btn-secondary:hover {
    background-color: var(--bs-gray-500) !important;
    border-color: var(--bs-gray-400) !important;
}

[data-bs-theme="dark"] .btn-success {
    background-color: var(--bs-green-600) !important;
    border-color: var(--bs-green-500) !important;
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .btn-success:hover {
    background-color: var(--bs-green-500) !important;
    border-color: var(--bs-green-400) !important;
}

[data-bs-theme="dark"] .btn-warning {
    background-color: var(--bs-yellow-600) !important;
    border-color: var(--bs-yellow-500) !important;
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .btn-warning:hover {
    background-color: var(--bs-yellow-500) !important;
    border-color: var(--bs-yellow-400) !important;
}

/* Dark mode breadcrumb styling */
[data-bs-theme="dark"] .breadcrumb {
    background-color: var(--bs-gray-800) !important;
    border-color: var(--bs-gray-600) !important;
}

[data-bs-theme="dark"] .breadcrumb-item {
    color: var(--bs-gray-300) !important;
}

[data-bs-theme="dark"] .breadcrumb-item.active {
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .breadcrumb-item a {
    color: var(--bs-primary) !important;
}

[data-bs-theme="dark"] .breadcrumb-item a:hover {
    color: var(--bs-primary-light) !important;
}

/* Dark mode page heading styling */
[data-bs-theme="dark"] .page-heading {
    background-color: var(--bs-gray-800) !important;
}

[data-bs-theme="dark"] .page-title h3 {
    color: var(--bs-gray-100) !important;
}

[data-bs-theme="dark"] .page-title .text-subtitle {
    color: var(--bs-gray-400) !important;
}

/* Custom color classes for dark mode */
[data-bs-theme="dark"] .text-white {
    color: #ffffff !important;
}

[data-bs-theme="dark"] .text-gray-300 {
    color: #d1d5db !important;
}

[data-bs-theme="dark"] .text-gray-400 {
    color: #9ca3af !important;
}

[data-bs-theme="dark"] .text-green-400 {
    color: #4ade80 !important;
}

[data-bs-theme="dark"] .text-red-400 {
    color: #f87171 !important;
}

[data-bs-theme="dark"] .text-cyan-400 {
    color: #22d3ee !important;
}

[data-bs-theme="dark"] .text-blue-400 {
    color: #60a5fa !important;
}

[data-bs-theme="dark"] .border-green-600 {
    border-color: #16a34a !important;
}

[data-bs-theme="dark"] .border-red-600 {
    border-color: #dc2626 !important;
}

[data-bs-theme="dark"] .border-blue-500 {
    border-color: #3b82f6 !important;
}

[data-bs-theme="dark"] .border-gray-600 {
    border-color: #4b5563 !important;
}

/* Dark mode footer styling */
[data-bs-theme="dark"] .footer {
    background-color: var(--bs-gray-800) !important;
    border-color: var(--bs-gray-600) !important;
}

[data-bs-theme="dark"] .footer,
[data-bs-theme="dark"] .footer p {
    color: var(--bs-gray-400) !important;
}

[data-bs-theme="dark"] .footer a {
    color: var(--bs-primary) !important;
}

/* Print styles - fit exactly 1 A4 page */
@media print {
    @page {
        size: A4 portrait;
        margin: 15mm 15mm 15mm 15mm;
    }

    /* Hide everything except print area */
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        width: 210mm;
        height: 297mm;
        overflow: hidden !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * {
        visibility: hidden;
    }

    #print-area, #print-area * {
        visibility: visible;
    }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: auto;
        max-height: 267mm; /* A4 height minus margins */
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
    }

    #print-area .card-body {
        padding: 0 !important;
    }

    .no-print, .page-heading > .page-title,
    .no-print *, .d-flex.gap-2.mb-3 {
        display: none !important;
    }

    /* Compact print typography */
    .payslip-container h4 { font-size: 14pt !important; margin-bottom: 0 !important; }
    .payslip-container h5 { font-size: 12pt !important; }
    .payslip-container h6 { font-size: 9pt !important; }
    .payslip-container p { font-size: 8.5pt !important; }
    .payslip-container hr { margin: 3px 0 !important; }

    .slip-info-table td { font-size: 8.5pt !important; padding: 1px 3px !important; }
    .slip-table td { font-size: 8.5pt !important; padding: 2px 3px !important; }

    /* Colored backgrounds for print */
    .border.rounded { border: 1px solid #ccc !important; }
    .payslip-dark-success { background: #f0fff0 !important; }
    .payslip-dark-danger { background: #fff0f0 !important; }
    .payslip-dark-primary { background: #e3f2fd !important; }
    .payslip-dark-info { background: #f5f5f5 !important; }

    .text-success { color: #198754 !important; }
    .text-danger { color: #dc3545 !important; }
    .text-primary { color: #0d6efd !important; }
    .text-muted { color: #6c757d !important; }
    .fw-bold { font-weight: 700 !important; }

    /* Signature area - push to fill page */
    .signature-area {
        margin-top: 40px !important;
        font-size: 8.5pt !important;
    }
    .signature-space {
        height: 50px !important;
    }

    /* Remove card styling */
    .card { box-shadow: none !important; border: none !important; background: white !important; }

    /* Ensure no page break */
    .payslip-container { page-break-inside: avoid; }
}
</style>

@push('scripts')
<script>
document.getElementById('btn-print').addEventListener('click', function() {
    window.print();
});
</script>
@endpush
@endsection
