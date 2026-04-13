@extends('layouts.dashboard')

@section('title', 'Input Transaksi Kas Baru')

@push('styles')
<style>
.form-hero {
    background: linear-gradient(135deg,#1a1f3c 0%,#2d3561 100%);
    border-radius: 16px; padding: 1.2rem 1.6rem; margin-bottom: 1.25rem;
}
.form-hero .fh-title { font-size:1rem; font-weight:800; color:#fff; margin:0; }
.form-hero .fh-sub   { font-size:.74rem; color:rgba(255,255,255,.5); margin:.2rem 0 0; }

.form-section-title {
    font-size:.68rem; font-weight:800; letter-spacing:.09em; text-transform:uppercase;
    color:#8392ab; border-left:3px solid #5e72e4; padding-left:.65rem; margin-bottom:1rem;
}
.fin-label { font-size:.78rem; font-weight:700; color:#344767; margin-bottom:.4rem; display:block; }
.fin-input {
    border-radius:10px; border:1.5px solid #e4e8f0; font-size:.85rem;
    padding:.6rem .9rem; transition:border-color .15s, box-shadow .15s; width:100%;
    background:#fff;
}
.fin-input:focus { border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12); outline:none; }
.fin-input.is-invalid { border-color:#f5365c; }

/* Debit/Kredit toggle */
.type-toggle { display:flex; gap:.6rem; }
.type-radio  { display:none; }
.type-btn {
    flex:1; padding:.75rem 1.2rem; border-radius:12px; cursor:pointer;
    border:2px solid #e4e8f0; background:#fff; transition:all .15s;
    display:flex; align-items:center; justify-content:center; gap:.6rem;
    font-size:.85rem; font-weight:700; color:#8392ab;
    user-select:none;
}
.type-btn .tb-icon { font-size:1.3rem; }
.type-btn .tb-label { line-height:1.2; }
.type-btn .tb-label small { font-size:.65rem; font-weight:500; display:block; }
.type-radio[value="debit"]:checked  + .type-btn { border-color:#1aae6f; background:#e2faf0; color:#1aae6f; box-shadow:0 0 0 3px rgba(26,174,111,.12); }
.type-radio[value="kredit"]:checked + .type-btn { border-color:#f5365c; background:#fce8e8; color:#f5365c; box-shadow:0 0 0 3px rgba(245,54,92,.12); }
.type-btn:hover { border-color:#adb5bd; background:#f8f9fa; }

/* Amount display */
.amount-preview {
    background:#f4f6fb; border-radius:10px; padding:.65rem 1rem;
    font-size:1.3rem; font-weight:900; letter-spacing:-.01em;
    text-align:center; border:1.5px solid #e4e8f0;
    transition:color .2s, background .2s;
}
.amount-preview.debit  { color:#1aae6f; background:#e6fbf2; border-color:#b0f0d4; }
.amount-preview.kredit { color:#f5365c; background:#fce8e8; border-color:#f5bfc8; }

/* Entity quick select */
.entity-quick {
    display:flex; flex-wrap:wrap; gap:.4rem; margin-top:.5rem;
}
.entity-chip {
    border:1.5px solid #e4e8f0; background:#fff; border-radius:7px;
    padding:.28rem .75rem; font-size:.73rem; font-weight:600; color:#525f7f;
    cursor:pointer; transition:all .12s;
}
.entity-chip:hover, .entity-chip.active { border-color:#5e72e4; background:#eef0ff; color:#5e72e4; }
</style>
@endpush

@section('content')

<div class="form-hero shadow">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p class="fh-title">➕ Input Transaksi Kas Baru</p>
            <p class="fh-sub">Catat transaksi debit (masuk) atau kredit (keluar) ke dalam buku kas</p>
        </div>
        <a href="{{ route('finance.transactions.index') }}" class="btn btn-sm mb-0"
           style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
            ← Kembali ke Ledger
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert mb-3 text-white py-2" style="background:#f5365c;border-radius:10px;font-size:.84rem">
        <i class="bi bi-exclamation-circle-fill me-1"></i>
        <strong>Periksa kembali isian:</strong>
        <ul class="mb-0 mt-1 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="row g-3">
    {{-- ── MAIN FORM ─────────────── --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">
            <div style="height:3px;background:linear-gradient(90deg,#5e72e4,#1aae6f,#f5365c)"></div>
            <div class="card-body p-4">
                <form action="{{ route('finance.transactions.store') }}" method="POST" id="trxForm">
                    @csrf

                    {{-- ① Tipe Transaksi --}}
                    <p class="form-section-title">① Tipe Transaksi</p>
                    <div class="type-toggle mb-4">
                        <input type="radio" name="transaction_type" id="type_debit" value="debit"
                               class="type-radio" {{ old('transaction_type','debit') === 'debit' ? 'checked':'' }}>
                        <label for="type_debit" class="type-btn">
                            <span class="tb-icon">📥</span>
                            <span class="tb-label">DEBIT<small>Uang Masuk / Penerimaan</small></span>
                        </label>

                        <input type="radio" name="transaction_type" id="type_kredit" value="kredit"
                               class="type-radio" {{ old('transaction_type') === 'kredit' ? 'checked':'' }}>
                        <label for="type_kredit" class="type-btn">
                            <span class="tb-icon">📤</span>
                            <span class="tb-label">KREDIT<small>Uang Keluar / Pembayaran</small></span>
                        </label>
                    </div>

                    {{-- ② Tanggal & Akun --}}
                    <p class="form-section-title">② Tanggal & Akun</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="fin-label" for="transaction_date">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                   class="fin-input @error('transaction_date') is-invalid @enderror"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')<div class="invalid-feedback" style="font-size:.75rem">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-7">
                            <label class="fin-label" for="account_id">Akun / CoA <span class="text-danger">*</span></label>
                            <select name="account_id" id="account_id"
                                    class="fin-input @error('account_id') is-invalid @enderror" required>
                                <option value="">— Pilih Akun —</option>
                                @php $cats = ['asset'=>'Harta','liability'=>'Kewajiban','equity'=>'Modal','revenue'=>'Pendapatan','expense'=>'Biaya']; @endphp
                                @foreach($cats as $cat => $label)
                                    @if($accounts->where('category',$cat)->count())
                                    <optgroup label="{{ $label }} ({{ ucfirst($cat) }})">
                                        @foreach($accounts->where('category',$cat) as $acc)
                                            <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected':'' }}>
                                                [{{ $acc->code }}] {{ $acc->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            @error('account_id')<div class="invalid-feedback" style="font-size:.75rem">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- ③ Nominal --}}
                    <p class="form-section-title">③ Nominal</p>
                    <div class="mb-4">
                        <label class="fin-label" for="amount">Jumlah (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group mb-2">
                            <span class="input-group-text fw-bold" style="background:#f4f6fb;border:1.5px solid #e4e8f0;border-right:0;border-radius:10px 0 0 10px;font-size:.85rem;color:#344767">Rp</span>
                            <input type="number" name="amount" id="amount"
                                   class="fin-input @error('amount') is-invalid @enderror"
                                   style="border-left:0;border-radius:0 10px 10px 0"
                                   value="{{ old('amount') }}"
                                   placeholder="0" min="0" step="any" required
                                   oninput="updateAmountPreview(this.value)">
                        </div>
                        <div class="amount-preview" id="amountPreview">Rp 0</div>
                        @error('amount')<div class="text-danger" style="font-size:.75rem;margin-top:.3rem">{{ $message }}</div>@enderror
                    </div>

                    {{-- ④ Keterangan --}}
                    <p class="form-section-title">④ Keterangan</p>
                    <div class="mb-4">
                        <label class="fin-label" for="description">Deskripsi / Keterangan <span class="text-danger">*</span></label>
                        <input type="text" name="description" id="description"
                               class="fin-input @error('description') is-invalid @enderror"
                               value="{{ old('description') }}"
                               placeholder="Contoh: Pembayaran sewa kantor Februari, Penerimaan dari klien ABC..." required>
                        @error('description')<div class="invalid-feedback" style="font-size:.75rem">{{ $message }}</div>@enderror
                    </div>

                    {{-- ⑤ Entitas --}}
                    <p class="form-section-title">⑤ Entitas (Opsional)</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fin-label" for="sender_entity_id">Entitas Pengirim (Dari)</label>
                            <select name="sender_entity_id" id="sender_entity_id" class="fin-input">
                                <option value="">— Tidak Ada —</option>
                                @foreach($entities as $ent)
                                    <option value="{{ $ent->id }}" {{ old('sender_entity_id') == $ent->id ? 'selected':'' }}>
                                        {{ $ent->name }} ({{ ucfirst($ent->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fin-label" for="receiver_entity_id">Entitas Penerima (Ke)</label>
                            <select name="receiver_entity_id" id="receiver_entity_id" class="fin-input">
                                <option value="">— Tidak Ada —</option>
                                @foreach($entities as $ent)
                                    <option value="{{ $ent->id }}" {{ old('receiver_entity_id') == $ent->id ? 'selected':'' }}>
                                        {{ $ent->name }} ({{ ucfirst($ent->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ⑥ Marker --}}
                    <p class="form-section-title">⑥ Penanda Periode (Opsional)</p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <div class="form-check form-switch" style="min-width:0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="is_end_of_month" name="is_end_of_month" value="1"
                                   {{ old('is_end_of_month') ? 'checked':'' }}>
                            <label class="form-check-label text-sm" for="is_end_of_month">
                                🗓 Tandai sebagai <strong>Akhir Bulan</strong>
                            </label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="is_end_of_year" name="is_end_of_year" value="1"
                                   {{ old('is_end_of_year') ? 'checked':'' }}>
                            <label class="form-check-label text-sm" for="is_end_of_year">
                                📅 Tandai sebagai <strong>Akhir Tahun</strong>
                            </label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('finance.transactions.index') }}" class="btn btn-outline-secondary mb-0" style="border-radius:9px">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-4" style="border-radius:9px">
                            <i class="bi bi-save me-1"></i>Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── SIDEBAR INFO ───────────── --}}
    <div class="col-lg-4">
        {{-- Summary saldo terakhir --}}
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;overflow:hidden">
            <div style="height:3px;background:linear-gradient(90deg,#1aae6f,#5e72e4)"></div>
            <div class="card-body p-3">
                <p class="text-xs fw-bold mb-3" style="color:#8392ab;letter-spacing:.08em;text-transform:uppercase">
                    💰 Ringkasan Saldo Saat Ini
                </p>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-xs text-muted">Total Debit (Masuk)</span>
                    <span class="text-success fw-bold text-sm">Rp {{ number_format(\App\Models\FinancialTransaction::where('transaction_type','debit')->sum('amount'),0,',','.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-xs text-muted">Total Kredit (Keluar)</span>
                    <span class="text-danger fw-bold text-sm">Rp {{ number_format(\App\Models\FinancialTransaction::where('transaction_type','kredit')->sum('amount'),0,',','.') }}</span>
                </div>
                @php
                    $latestBalance = \App\Models\FinancialTransaction::orderByDesc('transaction_date')->orderByDesc('id')->value('running_balance') ?? 0;
                @endphp
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-xs fw-bold" style="color:#344767">Saldo Bersih</span>
                    <span class="fw-bold" style="font-size:1rem;color:{{ $latestBalance < 0 ? '#f5365c' : '#5e72e4' }}">
                        {{ $latestBalance < 0 ? '−' : '' }}Rp {{ number_format(abs($latestBalance),0,',','.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Tips Card --}}
        <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden">
            <div style="height:3px;background:linear-gradient(90deg,#ffd600,#fb6340)"></div>
            <div class="card-body p-3">
                <p class="text-xs fw-bold mb-3" style="color:#8392ab;letter-spacing:.08em;text-transform:uppercase">
                    💡 Panduan Input
                </p>
                <div class="d-flex gap-2 mb-2">
                    <span style="font-size:1rem">📥</span>
                    <div>
                        <p class="text-xs fw-bold mb-0" style="color:#1aae6f">DEBIT = Uang Masuk</p>
                        <p class="text-xs text-muted mb-0">Penerimaan kas, pendapatan, setoran</p>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2">
                    <span style="font-size:1rem">📤</span>
                    <div>
                        <p class="text-xs fw-bold mb-0" style="color:#f5365c">KREDIT = Uang Keluar</p>
                        <p class="text-xs text-muted mb-0">Pembayaran, biaya, pengeluaran kas</p>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-2">
                    <span style="font-size:1rem">🔄</span>
                    <div>
                        <p class="text-xs fw-bold mb-0" style="color:#5e72e4">Running Balance</p>
                        <p class="text-xs text-muted mb-0">Dihitung otomatis setelah simpan</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <span style="font-size:1rem">📊</span>
                    <div>
                        <p class="text-xs fw-bold mb-0" style="color:#344767">Pilih Akun CoA</p>
                        <p class="text-xs text-muted mb-0">Klasifikasikan transaksi dengan benar agar laporan akurat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Live amount preview
function updateAmountPreview(val) {
    const preview = document.getElementById('amountPreview');
    const num = parseFloat(val) || 0;
    const formatted = 'Rp ' + num.toLocaleString('id-ID', {minimumFractionDigits:0});
    preview.textContent = formatted;

    const checkedType = document.querySelector('input[name="transaction_type"]:checked');
    preview.className = 'amount-preview ' + (checkedType ? checkedType.value : '');
}

// Update amount preview color when type changes
document.querySelectorAll('input[name="transaction_type"]').forEach(radio => {
    radio.addEventListener('change', () => {
        updateAmountPreview(document.getElementById('amount').value);
    });
});

// Init on load
window.addEventListener('DOMContentLoaded', () => {
    const amt = document.getElementById('amount').value;
    if (amt) updateAmountPreview(amt);
});
</script>
@endpush
