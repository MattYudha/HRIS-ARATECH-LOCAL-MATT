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
        @if(\App\Constants\Roles::isAdmin(session('role')))
            <a href="{{ route('payrolls.edit', $payroll->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
        @endif
        <span class="ms-auto">{!! $payroll->status_badge !!}</span>
    </div>

    {{-- ═══════════════════════════════════════════════════════ --}}
    {{-- PAYSLIP DOCUMENT                                       --}}
    {{-- ═══════════════════════════════════════════════════════ --}}
    <div class="payslip-wrapper" id="print-area">
        <div class="payslip-document">

            {{-- ── COMPANY HEADER ──────────────────────────────────── --}}
            <div class="payslip-letterhead">
                <div class="letterhead-brand">
                    <div class="brand-logo-circle">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="brand-text">
                        <div class="brand-name">PT. Aratech Nusantara Indonesia</div>
                        <div class="brand-address">Jl. Jend. Sudirman No. 55, Jakarta Pusat &nbsp;|&nbsp; (021) 5000-1234 &nbsp;|&nbsp; hrd@aratech.co.id</div>
                    </div>
                </div>
                <div class="letterhead-divider"></div>
                <div class="letterhead-title">
                    <div class="slip-title">SLIP GAJI KARYAWAN</div>
                    <div class="slip-period">Periode: {{ $payroll->period_label }}</div>
                </div>
            </div>

            {{-- ── EMPLOYEE INFO ────────────────────────────────────── --}}
            <div class="payslip-employee-info">
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-separator">:</span>
                        <span class="info-value fw-semibold">{{ $payroll->employee?->fullname ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NIK</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $payroll->employee?->emp_code ?? $payroll->employee?->nik ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Departemen</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $payroll->employee?->department?->name ?? '-' }}</span>
                    </div>
                </div>
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Jabatan</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $payroll->employee?->employeePositions?->where('is_active', true)->first()?->position?->position_name ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Bayar</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $payroll->pay_date?->format('d F Y') ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $payroll->status ?? 'draft' }}">{{ ucfirst($payroll->status ?? 'draft') }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- ── EARNINGS & DEDUCTIONS ────────────────────────────── --}}
            <div class="payslip-columns">

                {{-- PENDAPATAN --}}
                <div class="payslip-col">
                    <div class="col-header col-header-earnings">
                        <i class="bi bi-arrow-up-circle"></i>
                        <span>PENDAPATAN</span>
                    </div>
                    <table class="payslip-table">
                        <tbody>
                            <tr>
                                <td class="item-name">Gaji Pokok</td>
                                <td class="item-amount fw-semibold">Rp {{ number_format($payroll->salary, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->transport_allowance > 0)
                            <tr>
                                <td class="item-name">Tunjangan Transport</td>
                                <td class="item-amount">Rp {{ number_format($payroll->transport_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->meal_allowance > 0)
                            <tr>
                                <td class="item-name">Tunjangan Makan</td>
                                <td class="item-amount">Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->position_allowance > 0)
                            <tr>
                                <td class="item-name">Tunjangan Jabatan</td>
                                <td class="item-amount">Rp {{ number_format($payroll->position_allowance, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->overtime_amount > 0)
                            <tr>
                                <td class="item-name">Lembur ({{ $payroll->overtime_hours }} jam)</td>
                                <td class="item-amount">Rp {{ number_format($payroll->overtime_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->performance_bonus > 0)
                            <tr>
                                <td class="item-name">Bonus Kinerja</td>
                                <td class="item-amount">Rp {{ number_format($payroll->performance_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->attendance_bonus > 0)
                            <tr>
                                <td class="item-name">Bonus Kehadiran</td>
                                <td class="item-amount">Rp {{ number_format($payroll->attendance_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_bonus > 0)
                            <tr>
                                <td class="item-name">Bonus Lainnya</td>
                                <td class="item-amount">Rp {{ number_format($payroll->other_bonus, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->bonus_notes)
                            <tr>
                                <td colspan="2" class="item-note"><em>{{ $payroll->bonus_notes }}</em></td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td class="total-label">Total Pendapatan</td>
                                <td class="total-amount">Rp {{ number_format($payroll->total_earnings, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- POTONGAN --}}
                <div class="payslip-col">
                    <div class="col-header col-header-deductions">
                        <i class="bi bi-arrow-down-circle"></i>
                        <span>POTONGAN</span>
                    </div>
                    <table class="payslip-table">
                        <tbody>
                            @if($payroll->late_deduction > 0)
                            <tr>
                                <td class="item-name">Pot. Keterlambatan ({{ $payroll->late_count }}×)</td>
                                <td class="item-amount">Rp {{ number_format($payroll->late_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->absent_deduction > 0)
                            <tr>
                                <td class="item-name">Pot. Absen ({{ $payroll->absent_count }} hari)</td>
                                <td class="item-amount">Rp {{ number_format($payroll->absent_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->penalty_amount > 0)
                            <tr>
                                <td class="item-name">Denda / Penalti</td>
                                <td class="item-amount">Rp {{ number_format($payroll->penalty_amount, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->penalty_notes)
                            <tr>
                                <td colspan="2" class="item-note"><em>{{ $payroll->penalty_notes }}</em></td>
                            </tr>
                            @endif
                            @endif
                            @if($payroll->bpjs_kes > 0)
                            <tr>
                                <td class="item-name">BPJS Kesehatan</td>
                                <td class="item-amount">Rp {{ number_format($payroll->bpjs_kes, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->bpjs_tk > 0)
                            <tr>
                                <td class="item-name">BPJS Ketenagakerjaan</td>
                                <td class="item-amount">Rp {{ number_format($payroll->bpjs_tk, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->pph21 > 0)
                            <tr>
                                <td class="item-name">PPh 21</td>
                                <td class="item-amount">Rp {{ number_format($payroll->pph21, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($payroll->other_deduction > 0)
                            <tr>
                                <td class="item-name">Potongan Lainnya</td>
                                <td class="item-amount">Rp {{ number_format($payroll->other_deduction, 0, ',', '.') }}</td>
                            </tr>
                            @if($payroll->deduction_notes)
                            <tr>
                                <td colspan="2" class="item-note"><em>{{ $payroll->deduction_notes }}</em></td>
                            </tr>
                            @endif
                            @endif
                            @if($payroll->total_deductions == 0)
                            <tr>
                                <td colspan="2" class="item-note text-center py-3">Tidak ada potongan</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td class="total-label">Total Potongan</td>
                                <td class="total-amount">Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- ── KEHADIRAN ─────────────────────────────────────────── --}}
            @if($payroll->working_days > 0)
            <div class="payslip-attendance">
                <i class="bi bi-calendar3"></i>
                <strong>Rekap Kehadiran:</strong>
                <span>Hari Kerja: <strong>{{ $payroll->working_days }}</strong></span>
                <span class="att-sep">|</span>
                <span>Hadir: <strong>{{ $payroll->days_present }}</strong></span>
                <span class="att-sep">|</span>
                <span>Telat: <strong>{{ $payroll->late_count }}</strong></span>
                <span class="att-sep">|</span>
                <span>Absen: <strong>{{ $payroll->absent_count }}</strong></span>
            </div>
            @endif

            {{-- ── NET SALARY SUMMARY ───────────────────────────────── --}}
            <div class="payslip-summary">
                <table class="summary-table">
                    <tr>
                        <td class="sum-label">Total Pendapatan</td>
                        <td class="sum-amount">Rp {{ number_format($payroll->total_earnings, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="sum-label">Total Potongan</td>
                        <td class="sum-amount sum-deduction">- Rp {{ number_format($payroll->total_deductions, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="sum-net-row">
                        <td class="sum-net-label">GAJI BERSIH</td>
                        <td class="sum-net-amount">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            {{-- ── NOTES ────────────────────────────────────────────── --}}
            @if($payroll->notes)
            <div class="payslip-notes">
                <strong>Catatan:</strong> {{ $payroll->notes }}
            </div>
            @endif

            {{-- ── SIGNATURE ───────────────────────────────────────── --}}
            <div class="payslip-signature">
                <div class="sig-col">
                    <p class="sig-title">Diterima oleh,</p>
                    <div class="sig-space"></div>
                    <div class="sig-line"></div>
                    <p class="sig-name">{{ $payroll->employee?->fullname ?? '________________' }}</p>
                    <p class="sig-role">Karyawan</p>
                </div>
                <div class="sig-col-center"></div>
                <div class="sig-col">
                    <p class="sig-title">Disetujui oleh,</p>
                    <div class="sig-space"></div>
                    <div class="sig-line"></div>
                    <p class="sig-name">________________</p>
                    <p class="sig-role">
                        @if(\App\Constants\Roles::isAdmin(session('role')))
                            Master Admin
                        @else
                            HR Administrator / Manager
                        @endif
                    </p>
                </div>
            </div>

            {{-- ── FOOTER ───────────────────────────────────────────── --}}
            <div class="payslip-footer">
                <p>Dokumen ini diterbitkan secara resmi oleh sistem HRIS PT. Aratech Nusantara Indonesia.</p>
                <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
            </div>

        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════════════════════════
   PAYSLIP — Professional Clean Design
   ═══════════════════════════════════════════════════════════════ */

.payslip-wrapper {
    max-width: 860px;
    margin: 0 auto 40px;
    font-family: 'Segoe UI', 'Inter', sans-serif;
}

.payslip-document {
    background: #ffffff;
    border: 1px solid #dde1e7;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
}

/* ── Letterhead ──────────────────────────────────────────────── */
.payslip-letterhead {
    background: #1e3a5f;
    color: #fff;
    padding: 24px 32px 20px;
}

.letterhead-brand {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.brand-logo-circle {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}

.brand-name {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: 0.3px;
}

.brand-address {
    font-size: 0.73rem;
    color: rgba(255,255,255,0.7);
    margin-top: 2px;
}

.letterhead-divider {
    border-top: 1px solid rgba(255,255,255,0.2);
    margin-bottom: 14px;
}

.letterhead-title {
    text-align: center;
}

.slip-title {
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.slip-period {
    font-size: 0.8rem;
    color: rgba(255,255,255,0.75);
    margin-top: 3px;
}

/* ── Employee Info ───────────────────────────────────────────── */
.payslip-employee-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    padding: 16px 32px;
    background: #f8f9fb;
    border-bottom: 1px solid #dde1e7;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-row {
    display: flex;
    align-items: baseline;
    font-size: 0.82rem;
    color: #374151;
}

.info-label {
    width: 120px;
    color: #6b7280;
    flex-shrink: 0;
}

.info-separator {
    margin: 0 8px;
    color: #9ca3af;
}

.info-value {
    color: #111827;
}

.status-badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-paid    { background: #dcfce7; color: #166534; }
.status-approved{ background: #dbeafe; color: #1e40af; }
.status-draft   { background: #fef9c3; color: #854d0e; }

/* ── Two-column table area ───────────────────────────────────── */
.payslip-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    border-bottom: 1px solid #dde1e7;
}

.payslip-col {
    padding: 20px 24px;
}

.payslip-col:first-child {
    border-right: 1px solid #dde1e7;
}

.col-header {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
    padding-bottom: 10px;
    margin-bottom: 8px;
    border-bottom: 2px solid;
}

.col-header-earnings  { color: #155e30; border-color: #22c55e; }
.col-header-deductions{ color: #991b1b; border-color: #ef4444; }

.payslip-table {
    width: 100%;
    border-collapse: collapse;
}

.payslip-table tbody tr:hover {
    background: #f9fafb;
}

.item-name {
    font-size: 0.8rem;
    color: #374151;
    padding: 4px 4px 4px 0;
    width: 65%;
}

.item-amount {
    font-size: 0.8rem;
    color: #111827;
    text-align: right;
    padding: 4px 0;
    white-space: nowrap;
}

.item-note {
    font-size: 0.72rem;
    color: #9ca3af;
    padding: 0 4px 4px 0;
}

.payslip-table tfoot .total-row {
    border-top: 1.5px solid #e5e7eb;
}

.total-label {
    font-size: 0.82rem;
    font-weight: 700;
    color: #111827;
    padding: 8px 4px 4px 0;
}

.total-amount {
    font-size: 0.82rem;
    font-weight: 700;
    color: #111827;
    text-align: right;
    padding: 8px 0 4px;
    white-space: nowrap;
}

/* ── Attendance Bar ──────────────────────────────────────────── */
.payslip-attendance {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 32px;
    font-size: 0.8rem;
    color: #374151;
    background: #f8f9fb;
    border-bottom: 1px solid #dde1e7;
}

.payslip-attendance i {
    color: #1e3a5f;
}

.att-sep {
    color: #d1d5db;
    margin: 0 2px;
}

/* ── Summary Box ─────────────────────────────────────────────── */
.payslip-summary {
    display: flex;
    justify-content: flex-end;
    padding: 16px 32px;
    border-bottom: 1px solid #dde1e7;
}

.summary-table {
    width: 340px;
    border-collapse: collapse;
}

.sum-label {
    font-size: 0.82rem;
    color: #374151;
    padding: 5px 16px 5px 0;
}

.sum-amount {
    font-size: 0.82rem;
    color: #111827;
    text-align: right;
    padding: 5px 0;
    white-space: nowrap;
}

.sum-deduction {
    color: #dc2626;
}

.sum-net-row {
    border-top: 2px solid #1e3a5f;
}

.sum-net-label {
    font-size: 0.95rem;
    font-weight: 800;
    color: #1e3a5f;
    padding: 10px 16px 10px 0;
    letter-spacing: 0.5px;
}

.sum-net-amount {
    font-size: 0.95rem;
    font-weight: 800;
    color: #1e3a5f;
    text-align: right;
    padding: 10px 0;
    white-space: nowrap;
}

/* ── Notes ───────────────────────────────────────────────────── */
.payslip-notes {
    padding: 12px 32px;
    font-size: 0.78rem;
    color: #6b7280;
    border-bottom: 1px solid #dde1e7;
    background: #fefce8;
}

/* ── Signature Area ──────────────────────────────────────────── */
.payslip-signature {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    padding: 24px 32px 28px;
    border-bottom: 1px solid #dde1e7;
}

.sig-col {
    text-align: center;
}

.sig-col-center {
    /* spacer */
}

.sig-title {
    font-size: 0.79rem;
    color: #374151;
    margin-bottom: 0;
}

.sig-space {
    height: 60px;
}

.sig-line {
    border-top: 1px solid #374151;
    width: 160px;
    margin: 0 auto 6px;
}

.sig-name {
    font-size: 0.82rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 2px;
}

.sig-role {
    font-size: 0.73rem;
    color: #6b7280;
    margin-bottom: 0;
}

/* ── Document Footer ─────────────────────────────────────────── */
.payslip-footer {
    text-align: center;
    padding: 10px 32px;
    background: #f8f9fb;
    border-top: 1px solid #dde1e7;
}

.payslip-footer p {
    font-size: 0.7rem;
    color: #9ca3af;
    margin: 1px 0;
}

/* ══════════════════════════════════════════════════════════════
   PRINT STYLES
   ══════════════════════════════════════════════════════════════ */
@media print {
    @page {
        size: A4 portrait;
        margin: 12mm 14mm 12mm 14mm;
    }

    html, body {
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; }

    #print-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .no-print, .page-heading > .page-title, .d-flex.gap-2.mb-3 {
        display: none !important;
    }

    .payslip-wrapper { max-width: 100%; margin: 0; }
    .payslip-document { box-shadow: none !important; border-radius: 0; }

    /* Force white backgrounds */
    .payslip-letterhead { background: #1e3a5f !important; }
    .payslip-employee-info { background: #f8f9fb !important; }
    .payslip-attendance { background: #f8f9fb !important; }
    .payslip-footer { background: #f8f9fb !important; }
    .payslip-notes { background: #fefce8 !important; }

    /* Typography scale-down for print */
    .brand-name { font-size: 10.5pt !important; }
    .brand-address { font-size: 7pt !important; }
    .slip-title { font-size: 9.5pt !important; }
    .slip-period { font-size: 8pt !important; }
    .info-row { font-size: 8pt !important; }
    .item-name, .item-amount { font-size: 7.5pt !important; }
    .total-label, .total-amount { font-size: 8pt !important; }
    .sum-label, .sum-amount { font-size: 8pt !important; }
    .sum-net-label, .sum-net-amount { font-size: 9pt !important; }
    .sig-title, .sig-name, .sig-role { font-size: 7.5pt !important; }
    .payslip-footer p { font-size: 6.5pt !important; }
    .payslip-attendance { font-size: 7.5pt !important; }
    .payslip-notes { font-size: 7.5pt !important; }

    .sig-space { height: 40px !important; }
    .payslip-col { padding: 12px 18px !important; }
    .payslip-employee-info { padding: 12px 24px !important; }
    .payslip-signature { padding: 16px 24px 20px !important; }
    .payslip-summary { padding: 12px 24px !important; }
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
