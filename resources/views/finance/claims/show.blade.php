@extends('layouts.dashboard')

@section('title', 'Detail Klaim #' . $claim->id)

@push('styles')
<style>
/* ════════════════════════════════════════════════════
   FINANCE CLAIM DETAIL — Clean Professional
   ════════════════════════════════════════════════════ */
:root {
    --fc-navy:    #1b2a4a;
    --fc-slate:   #3d4e6c;
    --fc-muted:   #7486a4;
    --fc-border:  #e2e7f0;
    --fc-soft:    #f7f9fc;
    --fc-white:   #ffffff;
    --fc-brand:   #1e3a5f;
    --fc-focus:   rgba(30,58,95,0.08);
}

/* ── Page Header ───────────────────────────────────── */
.cs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid var(--fc-border);
    margin-bottom: 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}
.cs-breadcrumb {
    font-size: .75rem;
    color: var(--fc-muted);
    display: flex;
    align-items: center;
    gap: .3rem;
    margin-bottom: .25rem;
}
.cs-breadcrumb a { color: var(--fc-muted); text-decoration: none; }
.cs-breadcrumb a:hover { color: var(--fc-navy); }
.cs-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--fc-navy);
    letter-spacing: -.02em;
    margin: 0;
}
.cs-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem 1rem;
    font-size: .8rem;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all .15s;
    text-decoration: none;
    font-family: inherit;
    line-height: 1;
}
.cs-btn-ghost {
    background: var(--fc-white);
    border-color: var(--fc-border);
    color: var(--fc-slate);
}
.cs-btn-ghost:hover { background: var(--fc-soft); color: var(--fc-navy); }

/* ── Main card ─────────────────────────────────────── */
.cs-card {
    background: var(--fc-white);
    border: 1px solid var(--fc-border);
    border-radius: 12px;
    box-shadow: 0 1px 6px rgba(0,0,0,.04);
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.cs-card-header {
    padding: 1rem 1.5rem;
    background: #fafbfd;
    border-bottom: 1px solid var(--fc-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cs-card-title {
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .12em;
    color: var(--fc-muted);
    margin: 0;
}
.cs-card-body { padding: 1.5rem; }

/* ── Status badges ─────────────────────────────────── */
.cs-badge {
    display: inline-block;
    padding: .2rem .65rem;
    border-radius: 6px;
    font-size: .65rem;
    font-weight: 800;
    letter-spacing: .05em;
    line-height: 1;
    text-transform: uppercase;
}
.cs-badge-pending  { background: #fefce8; color: #92400e; border: 1px solid #fde68a; }
.cs-badge-approved { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.cs-badge-rejected { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ── Info layout ───────────────────────────────────── */
.cs-hero-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--fc-soft);
    margin-bottom: 1.5rem;
}
.cs-amount {
    text-align: right;
}
.cs-amount-val {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--fc-navy);
    letter-spacing: -.03em;
    margin-bottom: .15rem;
    line-height: 1.1;
}
.cs-amount-lbl {
    font-size: .65rem;
    font-weight: 700;
    color: var(--fc-muted);
    text-transform: uppercase;
    letter-spacing: .08em;
}

.cs-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
}
.cs-info-item {}
.cs-info-lbl {
    font-size: .68rem;
    font-weight: 700;
    color: var(--fc-muted);
    text-transform: uppercase;
    letter-spacing: .1em;
    margin-bottom: .35rem;
}
.cs-info-val {
    font-size: .88rem;
    font-weight: 700;
    color: var(--fc-navy);
    line-height: 1.4;
}
.cs-info-sub {
    font-size: .75rem;
    color: var(--fc-muted);
    margin-top: .15rem;
}

/* ── Deskripsi / Note box ───────────────────────────── */
.cs-note-box {
    background: var(--fc-soft);
    border: 1px solid var(--fc-border);
    border-radius: 10px;
    padding: 1rem;
    font-size: .85rem;
    color: var(--fc-slate);
    line-height: 1.6;
}

/* ── Reviewer section ──────────────────────────────── */
.cs-review-trail {
    margin-top: 1.5rem;
    padding: 1.25rem;
    border-radius: 10px;
    border-left: 4px solid var(--fc-border);
}
.cs-review-trail.trail-approved { background: #f0faf5; border-left-color: #2d6a4f; }
.cs-review-trail.trail-rejected { background: #fdf5f5; border-left-color: #9d2129; }

.cs-audit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: .75rem;
}
.cs-audit-lbl {
    font-size: .65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
}
.trail-approved .cs-audit-lbl { color: #155c38; }
.trail-rejected .cs-audit-lbl { color: #7b1d22; }

/* ── Attachment preview ────────────────────────────── */
.cs-attachment-wrap {
    background: #fcfdfe;
    border: 2.5px dashed var(--fc-border);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
    transition: all .2s;
}
.cs-img-preview {
    max-width: 100%;
    max-height: 500px;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,.08);
    display: block;
    margin: 0 auto;
}
.cs-pdf-preview {
    padding: 2.5rem 0;
}
.cs-pdf-icon { font-size: 3rem; color: #dc2626; opacity: .8; }

/* ── Sidebar Actions ──────────────────────────────── */
.cs-action-card {
    background: var(--fc-navy);
    border-radius: 12px;
    padding: 1.5rem;
    color: #fff;
    box-shadow: 0 4px 20px rgba(0,0,0,.15);
}
.cs-action-title { font-size: 1rem; font-weight: 700; margin-bottom: .25rem; }
.cs-action-sub   { font-size: .75rem; color: rgba(255,255,255,.6); margin-bottom: 1.25rem; }

.cs-form-label {
    display: block;
    font-size: .68rem;
    font-weight: 700;
    text-transform: uppercase;
    color: rgba(255,255,255,.7);
    margin-bottom: .45rem;
    letter-spacing: .08em;
}
.cs-form-input {
    width: 100%;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 8px;
    color: #fff;
    padding: .75rem;
    font-size: .82rem;
    outline: none;
    transition: all .15s;
    font-family: inherit;
    resize: vertical;
}
.cs-form-input:focus {
    background: rgba(255,255,255,.1);
    border-color: rgba(255,255,255,0.3);
}

.cs-btn-action {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    width: 100%;
    padding: .75rem;
    border-radius: 8px;
    font-weight: 700;
    font-size: .85rem;
    cursor: pointer;
    border: none;
    transition: all .15s;
    font-family: inherit;
}
.cs-btn-approve { background: #22c55e; color: #fff; }
.cs-btn-approve:hover { background: #16a34a; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(34,197,94,0.3); }
.cs-btn-reject { background: #ef4444; color: #fff; }
.cs-btn-reject:hover { background: #dc2626; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.3); }

/* ── Mobile ────────────────────────────────────────── */
@media (max-width: 767px) {
    .cs-header { flex-direction: column; align-items: flex-start; }
    .cs-hero-row { flex-direction: column; gap: 1rem; }
    .cs-amount { text-align: left; }
    .cs-grid { grid-template-columns: 1fr; gap: 1.25rem; }
}
</style>
@endpush

@section('content')

<div class="cs-header">
    <div>
        <div class="cs-breadcrumb">
            <a href="{{ route('finance.claims.index') }}">Pengajuan Klaim</a>
            <span>/</span>
            <span>Detail Klaim</span>
        </div>
        <h1 class="cs-title">Detail Klaim Karyawan</h1>
    </div>
    <a href="{{ route('finance.claims.index') }}" class="cs-btn cs-btn-ghost">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Alerts --}}
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3" style="font-size:.84rem; border-radius:10px">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:.84rem; border-radius:10px">
        <i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}
    </div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        
        {{-- Information Card --}}
        <div class="cs-card">
            <div class="cs-card-header">
                <p class="cs-card-title">Informasi Pengajuan</p>
                <span class="cs-badge cs-badge-{{ $claim->status }}">
                    {{ $claim->statusLabel() }}
                </span>
            </div>
            <div class="cs-card-body">
                <div class="cs-hero-row">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">{{ $claim->title }}</h4>
                        <div class="d-flex align-items-center gap-2 text-muted" style="font-size:.82rem">
                            <span>Diajukan oleh <strong>{{ $claim->employee->full_name }}</strong></span>
                            <span>•</span>
                            <span>{{ $claim->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    <div class="cs-amount">
                        <div class="cs-amount-val">Rp {{ number_format($claim->amount,0,',','.') }}</div>
                        <div class="cs-amount-lbl">Total Nilai Klaim</div>
                    </div>
                </div>

                <div class="cs-grid mb-4">
                    <div class="cs-info-item">
                        <p class="cs-info-lbl">Kategori Biaya</p>
                        <p class="cs-info-val">{{ $claim->categoryLabel() }}</p>
                    </div>
                    <div class="cs-info-item">
                        <p class="cs-info-lbl">ID Karyawan</p>
                        <p class="cs-info-val">{{ $claim->employee->employee_id }}</p>
                    </div>
                    <div class="cs-info-item">
                        <p class="cs-info-lbl">Akun Biaya (CoA)</p>
                        <p class="cs-info-val">[{{ $claim->account->code ?? '—' }}] {{ $claim->account->name ?? '—' }}</p>
                    </div>
                    <div class="cs-info-item">
                        <p class="cs-info-lbl">Status Terakhir</p>
                        <p class="cs-info-val">{{ $claim->statusLabel() }}</p>
                        <p class="cs-info-sub">Diperbarui {{ $claim->updated_at->diffForHumans() }}</p>
                    </div>
                </div>

                <div>
                    <p class="cs-info-lbl">Rincian / Deskripsi</p>
                    <div class="cs-note-box">
                        {{ $claim->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>

                {{-- Review Result --}}
                @if($claim->status !== 'pending')
                <div class="cs-review-trail trail-{{ $claim->status }}">
                    <div class="cs-audit-header">
                        <div class="cs-audit-lbl">
                            <i class="bi bi-shield-check me-1"></i>
                            Hasil Peninjauan: {{ $claim->statusLabel() }}
                        </div>
                        <span style="font-size:.7rem; color:var(--fc-muted)">{{ $claim->updated_at->format('d M Y') }}</span>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <p class="cs-info-lbl mb-1" style="font-size:.6rem">Catatan Reviewer</p>
                            <p class="text-sm mb-0" style="color:var(--fc-navy); font-weight:600">
                                "{{ $claim->review_notes ?: '—' }}"
                            </p>
                        </div>
                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                            <p class="cs-info-lbl mb-1" style="font-size:.6rem">Reviewer</p>
                            <p class="text-sm fw-bold mb-0 text-dark">{{ $claim->reviewer->name ?? '—' }}</p>
                        </div>
                    </div>
                    @if($claim->transaction_id)
                        <div class="mt-3 pt-2 border-top border-dark-subtle">
                            <p class="text-xs mb-0">
                                <span class="text-muted fw-bold">REFERENCE:</span>
                                <span class="ms-1">Jurnal Transaksi #{{ $claim->transaction_id }}</span>
                                <a href="{{ route('finance.transactions.index', ['search' => 'Reimburse klaim: ' . $claim->title]) }}" 
                                   class="ms-2 fw-bold text-primary text-decoration-underline">
                                    Lihat di Ledger <i class="bi bi-arrow-right"></i>
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Attachment Card --}}
        <div class="cs-card">
            <div class="cs-card-header">
                <p class="cs-card-title">Bukti Lampiran / Kuitansi</p>
            </div>
            <div class="cs-card-body">
                <div class="cs-attachment-wrap">
                    @if($claim->attachment_path)
                        @php $ext = pathinfo($claim->attachment_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ Storage::url($claim->attachment_path) }}" class="cs-img-preview" alt="Bukti Klaim">
                        @else
                            <div class="cs-pdf-preview text-center">
                                <i class="bi bi-file-earmark-pdf cs-pdf-icon"></i>
                                <p class="fw-bold mt-2 mb-3">Dokumen Lampiran ({{ strtoupper($ext) }})</p>
                                <a href="{{ Storage::url($claim->attachment_path) }}" target="_blank" class="cs-btn cs-btn-ghost">
                                    <i class="bi bi-box-arrow-up-right"></i> Buka Lampiran di Tab Baru
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="py-5 text-center">
                            <i class="bi bi-image-fill text-muted" style="font-size:3rem; opacity:.1"></i>
                            <p class="text-muted text-xs mt-3">Lampiran tidak tersedia untuk klaim ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Actions (Admin Review) --}}
    @if($isAdmin && $claim->isPending())
    <div class="col-lg-4">
        <div class="cs-action-card sticky-top" style="top:1.5rem">
            <h5 class="cs-action-title">Proses Peninjauan</h5>
            <p class="cs-action-sub">Tinjau rincian biaya dan lampiran sebelum memberikan keputusan.</p>
            
            <form id="actionForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="cs-form-label" for="review_notes">Catatan Untuk Karyawan <span class="text-danger">*</span></label>
                    <textarea name="review_notes" id="review_notes" rows="4" 
                              class="cs-form-input" 
                              placeholder="Berikan alasan persetujuan atau detail penolakan..." required></textarea>
                </div>

                <div class="d-grid gap-3">
                    <button type="submit" formaction="{{ route('finance.claims.approve', $claim->id) }}" 
                            class="cs-btn-action cs-btn-approve" 
                            onclick="return confirm('Setujui klaim ini? Jurnal transaksi akan dicatat otomatis ke Ledger.')">
                        <i class="bi bi-check2-circle"></i> SETUJUI PENGAJUAN
                    </button>
                    <button type="submit" formaction="{{ route('finance.claims.reject', $claim->id) }}" 
                            class="cs-btn-action cs-btn-reject" 
                            onclick="return confirm('Tolak pengajuan klaim ini?')">
                        <i class="bi bi-x-circle"></i> TOLAK PENGAJUAN
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

@endsection
