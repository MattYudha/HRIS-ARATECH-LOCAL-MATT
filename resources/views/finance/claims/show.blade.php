@extends('layouts.dashboard')

@section('title', 'Detail Klaim #' . $claim->id)

@push('styles')
<style>
.claim-card { border-radius:14px; border:none; overflow:hidden; }
.claim-badge { border-radius:6px; padding:.25rem .75rem; font-size:.75rem; font-weight:800; text-transform:uppercase; }
.badge-pending  { background:#fff4de; color:#d48a00; }
.badge-approved { background:#e2faf0; color:#1aae6f; }
.badge-rejected { background:#fce8e8; color:#f5365c; }

.info-label { font-size:.65rem; font-weight:800; color:#8392ab; text-transform:uppercase; letter-spacing:.05em; margin-bottom:.2rem; }
.info-value { font-size:.85rem; font-weight:700; color:#344767; margin-bottom:1rem; }

.attachment-frame {
    border-radius:12px; border:1.5px solid #edf0f7; background:#f8f9fc; padding:1rem; text-align:center;
}
.attachment-img { max-width:100%; border-radius:8px; box-shadow:0 4px 15px rgba(0,0,0,.08); }
</style>
@endpush

@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h5 class="fw-bold mb-0">Detail Klaim Karyawan</h5>
        <p class="text-xs text-muted mb-0">Lihat status dan rincian pengajuan reimbursement</p>
    </div>
    <a href="{{ route('finance.claims.index') }}" class="btn btn-sm btn-outline-secondary mb-0">← Kembali</a>
</div>

@if(session('success'))
    <div class="alert mb-3 text-white py-2" style="background:#1aae6f;border-radius:10px;font-size:.84rem">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert mb-3 text-white py-2" style="background:#f5365c;border-radius:10px;font-size:.84rem">{{ session('error') }}</div>
@endif

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card claim-card shadow-sm mb-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <span class="claim-badge badge-{{ $claim->status }}">{{ $claim->statusLabel() }}</span>
                        <h4 class="fw-bold mt-2 mb-0" style="color:#344767">{{ $claim->title }}</h4>
                        <p class="text-sm text-muted">Diajukan oleh <strong>{{ $claim->employee->full_name }}</strong> pada {{ $claim->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-end">
                        <p class="info-label">Total Klaim</p>
                        <h3 class="fw-black text-primary mb-0">Rp {{ number_format($claim->amount,0,',','.') }}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="info-label">Kategori</p>
                        <p class="info-value">{{ $claim->categoryLabel() }}</p>
                        
                        <p class="info-label">Akun Biaya (CoA)</p>
                        <p class="info-value">[{{ $claim->account->code ?? '—' }}] {{ $claim->account->name ?? '—' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="info-label">ID Karyawan</p>
                        <p class="info-value">{{ $claim->employee->employee_id }}</p>

                        <p class="info-label">Status Terakhir</p>
                        <p class="info-value">{{ $claim->statusLabel() }} ({{ $claim->updated_at->diffForHumans() }})</p>
                    </div>
                </div>

                <div class="mt-2">
                    <p class="info-label">Deskripsi</p>
                    <div class="p-3 bg-light rounded" style="font-size:.85rem; color:#525f7f; border:1px solid #eef0f7">
                        {{ $claim->description ?: 'Tidak ada deskripsi tambahan.' }}
                    </div>
                </div>

                @if($claim->status !== 'pending')
                <div class="mt-4 p-3 rounded" style="background:{{ $claim->status === 'approved' ? '#e2faf0' : '#fce8e8' }}">
                    <p class="info-label mb-1" style="color:{{ $claim->status === 'approved' ? '#1aae6f' : '#f5365c' }}">Reviewer: {{ $claim->reviewer->name ?? '—' }}</p>
                    <p class="text-sm fw-bold mb-1" style="color:#344767">Catatan Review:</p>
                    <p class="text-sm mb-0">{{ $claim->review_notes ?: '—' }}</p>
                    @if($claim->transaction_id)
                        <hr class="my-2 opacity-10">
                        <p class="text-xs mb-0">
                            <strong>Reference:</strong> Jurnal Transaksi #{{ $claim->transaction_id }} 
                            <a href="{{ route('finance.transactions.index', ['search' => 'Reimburse klaim: ' . $claim->title]) }}" class="ms-1 fw-bold">Lihat di Ledger →</a>
                        </p>
                    @endif
                </div>
                @endif
            </div>
        </div>

        {{-- Attachment --}}
        <div class="card claim-card shadow-sm">
            <div class="card-header bg-white border-0 pb-0">
                <h6 class="fw-bold text-sm mb-0">Bukti Lampiran</h6>
            </div>
            <div class="card-body">
                <div class="attachment-frame">
                    @if($claim->attachment_path)
                        @php $ext = pathinfo($claim->attachment_path, PATHINFO_EXTENSION); @endphp
                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif']))
                            <img src="{{ Storage::url($claim->attachment_path) }}" class="attachment-img" alt="Bukti Klaim">
                        @else
                            <div class="py-4">
                                <i class="bi bi-file-earmark-pdf text-danger" style="font-size:3rem"></i>
                                <p class="text-sm mt-2">Dokuemn PDF</p>
                                <a href="{{ Storage::url($claim->attachment_path) }}" target="_blank" class="btn btn-sm btn-primary">Buka Lampiran</a>
                            </div>
                        @endif
                    @else
                        <div class="py-5">
                            <i class="bi bi-image-fill text-muted" style="font-size:3rem; opacity:.2"></i>
                            <p class="text-muted text-xs mt-2">Lampiran tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Actions (Admin Only) --}}
    @if($isAdmin && $claim->isPending())
    <div class="col-lg-4">
        <div class="card claim-card shadow-sm sticky-top" style="top:1.5rem">
            <div class="card-header bg-dark text-white p-3">
                <h6 class="mb-0 text-white fw-bold">Proses Persetujuan</h6>
                <p class="text-xs mb-0 opacity-70">Tinjau dan putuskan pengajuan ini</p>
            </div>
            <div class="card-body p-4">
                <form id="actionForm" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="info-label" for="review_notes">Catatan Review <span class="text-danger">*</span></label>
                        <textarea name="review_notes" id="review_notes" rows="4" class="form-control text-sm" placeholder="Berikan alasan persetujuan atau penolakan..." required></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" formaction="{{ route('finance.claims.approve', $claim->id) }}" class="btn btn-success fw-bold" onclick="return confirm('Apakah Anda yakin menyetujui klaim ini? Jurnal transaksi akan dibuat otomatis.')">
                            <i class="bi bi-check-circle me-1"></i> SETUJUI KLAIM
                        </button>
                        <button type="submit" formaction="{{ route('finance.claims.reject', $claim->id) }}" class="btn btn-danger fw-bold" onclick="return confirm('Apakah Anda yakin menolak klaim ini?')">
                            <i class="bi bi-x-circle me-1"></i> TOLAK KLAIM
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
