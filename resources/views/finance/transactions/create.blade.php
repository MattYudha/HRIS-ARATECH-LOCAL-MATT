@extends('layouts.dashboard')

@section('title', 'Catat Transaksi Baru')

@push('styles')
<style>
/* ─── Enterprise Finance Form System ─────────────────────────────── */
:root {
    --ef-navy:        #1b2a4a;
    --ef-slate:       #3d4e6c;
    --ef-muted:       #6b7a99;
    --ef-border:      #dce1ec;
    --ef-border-soft: #eaecf3;
    --ef-bg:          #f5f7fa;
    --ef-white:       #ffffff;
    --ef-debit-text:  #155c38;
    --ef-debit-bg:    #eef7f2;
    --ef-debit-ring:  #2d6a4f;
    --ef-kredit-text: #7b1d22;
    --ef-kredit-bg:   #fdf0f1;
    --ef-kredit-ring: #9d2129;
}

/* Page Header */
.ef-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 1.2rem;
    border-bottom: 1px solid var(--ef-border);
    margin-bottom: 1.75rem;
}
.ef-breadcrumb {
    font-size: .73rem;
    color: var(--ef-muted);
    margin-bottom: .3rem;
    display: flex;
    align-items: center;
    gap: .35rem;
}
.ef-breadcrumb a {
    color: var(--ef-muted);
    text-decoration: none;
}
.ef-breadcrumb a:hover { color: var(--ef-navy); }
.ef-breadcrumb .sep { opacity: .5; }
.ef-page-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--ef-navy);
    letter-spacing: -.02em;
    margin: 0;
}
.ef-btn-back {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .42rem 1rem;
    font-size: .77rem;
    font-weight: 600;
    border: 1px solid var(--ef-border);
    border-radius: 6px;
    background: var(--ef-white);
    color: var(--ef-slate);
    text-decoration: none;
    transition: all .15s;
    white-space: nowrap;
}
.ef-btn-back:hover {
    background: var(--ef-bg);
    color: var(--ef-navy);
}

/* Main Card */
.ef-card {
    background: var(--ef-white);
    border: 1px solid var(--ef-border);
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(27,42,74,.06);
    overflow: hidden;
}
.ef-card-header {
    display: flex;
    align-items: center;
    gap: .55rem;
    padding: .9rem 1.5rem;
    border-bottom: 1px solid var(--ef-border-soft);
    background: #fcfcfe;
}
.ef-card-icon {
    font-size: .9rem;
    color: var(--ef-muted);
    line-height: 1;
}
.ef-card-title-text {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--ef-muted);
    margin: 0;
}
.ef-card-body { padding: 1.6rem; }

/* Form Fields */
.ef-field { margin-bottom: 1.25rem; }
.ef-label {
    display: block;
    font-size: .77rem;
    font-weight: 700;
    color: var(--ef-navy);
    margin-bottom: .45rem;
    letter-spacing: -.01em;
}
.ef-label .req { color: #b91c1c; font-weight: 400; margin-left: .1rem; }
.ef-label .opt {
    font-size: .68rem;
    font-weight: 400;
    color: var(--ef-muted);
    margin-left: .35rem;
}

.ef-input,
.ef-select,
.ef-textarea {
    display: block;
    width: 100%;
    padding: .52rem .9rem;
    font-size: .83rem;
    font-family: inherit;
    color: var(--ef-navy);
    background: var(--ef-white);
    border: 1px solid var(--ef-border);
    border-radius: 6px;
    transition: border-color .15s, box-shadow .15s;
    appearance: none;
    -webkit-appearance: none;
}
.ef-input:focus,
.ef-select:focus,
.ef-textarea:focus {
    outline: none;
    border-color: #8baed6;
    box-shadow: 0 0 0 3px rgba(75,131,200,.12);
}
.ef-input.ef-error,
.ef-select.ef-error,
.ef-textarea.ef-error { border-color: #c0392b; }
.ef-select {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' fill='%236b7a99' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right .8rem center;
    padding-right: 2.25rem;
    cursor: pointer;
}
.ef-textarea { resize: vertical; min-height: 88px; line-height: 1.55; }
.ef-field-error { font-size: .7rem; color: #b91c1c; margin-top: .3rem; }
.ef-field-hint { font-size: .7rem; color: var(--ef-muted); margin-top: .3rem; }

/* Input with prefix */
.ef-input-prefix-group { display: flex; }
.ef-prefix-label {
    display: flex;
    align-items: center;
    padding: .52rem .85rem;
    font-size: .8rem;
    font-weight: 700;
    color: var(--ef-muted);
    background: var(--ef-bg);
    border: 1px solid var(--ef-border);
    border-right: 0;
    border-radius: 6px 0 0 6px;
    white-space: nowrap;
    letter-spacing: .02em;
}
.ef-input-prefix-group .ef-input {
    border-radius: 0 6px 6px 0;
    border-left-color: transparent;
}
.ef-input-prefix-group .ef-input:focus { border-left-color: #8baed6; }

/* Amount Result Display */
.ef-amount-display {
    display: flex;
    align-items: baseline;
    gap: .65rem;
    padding: .75rem 1rem;
    margin-top: .6rem;
    background: var(--ef-bg);
    border: 1px solid var(--ef-border-soft);
    border-radius: 6px;
}
.ef-amount-display-label {
    font-size: .69rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--ef-muted);
    white-space: nowrap;
}
.ef-amount-display-value {
    font-size: 1.25rem;
    font-weight: 800;
    letter-spacing: -.03em;
    color: var(--ef-navy);
    transition: color .2s;
}
.ef-amount-display-value.debit  { color: var(--ef-debit-text); }
.ef-amount-display-value.kredit { color: var(--ef-kredit-text); }

/* Transaction Type Selector */
.ef-type-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: .6rem;
}
.ef-type-radio { position: absolute; opacity: 0; width: 0; height: 0; }
.ef-type-card {
    display: flex;
    align-items: center;
    gap: .75rem;
    padding: .8rem 1.1rem;
    border: 1.5px solid var(--ef-border);
    border-radius: 8px;
    cursor: pointer;
    background: var(--ef-white);
    transition: all .15s;
    position: relative;
}
.ef-type-card:hover {
    border-color: #b0bac9;
    background: #fafbfc;
}
.ef-type-radio:checked + .ef-type-card {
    box-shadow: 0 0 0 3px rgba(75,131,200,.1);
}
.ef-type-radio[value="debit"]:checked + .ef-type-card {
    border-color: var(--ef-debit-ring);
    background: var(--ef-debit-bg);
}
.ef-type-radio[value="kredit"]:checked + .ef-type-card {
    border-color: var(--ef-kredit-ring);
    background: var(--ef-kredit-bg);
}
.ef-type-indicator {
    width: 34px;
    height: 34px;
    border-radius: 7px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    flex-shrink: 0;
    background: var(--ef-bg);
    color: var(--ef-muted);
    transition: all .15s;
}
.ef-type-radio[value="debit"]:checked  ~ * .ef-type-indicator,
.ef-type-radio[value="debit"]:checked + .ef-type-card .ef-type-indicator {
    background: #d4eddf;
    color: var(--ef-debit-text);
}
.ef-type-radio[value="kredit"]:checked + .ef-type-card .ef-type-indicator {
    background: #f8d7da;
    color: var(--ef-kredit-text);
}
.ef-type-text-wrap { line-height: 1.3; }
.ef-type-main {
    font-size: .84rem;
    font-weight: 700;
    color: var(--ef-navy);
    display: block;
}
.ef-type-radio[value="debit"]:checked + .ef-type-card .ef-type-main { color: var(--ef-debit-text); }
.ef-type-radio[value="kredit"]:checked + .ef-type-card .ef-type-main { color: var(--ef-kredit-text); }
.ef-type-sub {
    font-size: .69rem;
    color: var(--ef-muted);
    display: block;
    font-weight: 400;
}
.ef-type-check {
    position: absolute;
    top: .55rem;
    right: .7rem;
    font-size: .75rem;
    color: var(--ef-muted);
    opacity: 0;
    transition: opacity .15s;
}
.ef-type-radio:checked + .ef-type-card .ef-type-check { opacity: 1; }
.ef-type-radio[value="debit"]:checked + .ef-type-card .ef-type-check { color: var(--ef-debit-ring); }
.ef-type-radio[value="kredit"]:checked + .ef-type-card .ef-type-check { color: var(--ef-kredit-ring); }

/* Section Divider */
.ef-divider {
    border: none;
    border-top: 1px solid var(--ef-border-soft);
    margin: 1.5rem 0;
}
.ef-section-heading {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--ef-muted);
    margin-bottom: 1rem;
}

/* Period Checkboxes */
.ef-check-row { display: flex; flex-wrap: wrap; gap: 1.25rem; }
.ef-check-wrap { display: flex; align-items: center; gap: .5rem; cursor: pointer; }
.ef-check-wrap input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: var(--ef-navy);
    cursor: pointer;
    flex-shrink: 0;
}
.ef-check-wrap label {
    font-size: .78rem;
    color: var(--ef-slate);
    cursor: pointer;
}

/* Action Buttons */
.ef-action-row {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: .6rem;
    padding-top: 1.4rem;
    border-top: 1px solid var(--ef-border-soft);
    margin-top: 1.5rem;
}
.ef-btn {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .52rem 1.3rem;
    font-size: .8rem;
    font-weight: 700;
    border-radius: 6px;
    cursor: pointer;
    border: 1px solid transparent;
    transition: all .15s;
    text-decoration: none;
    letter-spacing: .01em;
    font-family: inherit;
}
.ef-btn-secondary {
    background: var(--ef-white);
    border-color: var(--ef-border);
    color: var(--ef-slate);
}
.ef-btn-secondary:hover {
    background: var(--ef-bg);
    color: var(--ef-navy);
}
.ef-btn-primary {
    background: var(--ef-navy);
    border-color: var(--ef-navy);
    color: #fff;
}
.ef-btn-primary:hover {
    background: #14203a;
    border-color: #14203a;
}

/* ─── Sidebar ─────────────────────────────────────── */
.ef-sidebar-card {
    background: var(--ef-white);
    border: 1px solid var(--ef-border);
    border-radius: 10px;
    box-shadow: 0 1px 5px rgba(27,42,74,.05);
    overflow: hidden;
    margin-bottom: 1rem;
}
.ef-sidebar-header {
    display: flex;
    align-items: center;
    gap: .5rem;
    padding: .8rem 1.1rem;
    border-bottom: 1px solid var(--ef-border-soft);
    background: #fcfcfe;
}
.ef-sidebar-heading {
    font-size: .67rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .1em;
    color: var(--ef-muted);
    margin: 0;
}
.ef-sidebar-body { padding: 1.1rem; }

/* Balance rows */
.ef-bal-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .55rem 0;
    border-bottom: 1px solid var(--ef-border-soft);
    font-size: .8rem;
}
.ef-bal-row:last-child { border-bottom: none; padding-bottom: 0; }
.ef-bal-key { color: var(--ef-muted); }
.ef-bal-val { font-weight: 700; color: var(--ef-navy); }
.ef-bal-val.green { color: var(--ef-debit-text); }
.ef-bal-val.red   { color: var(--ef-kredit-text); }
.ef-bal-row-total { padding-top: .75rem; margin-top: .35rem; border-top: 1.5px solid var(--ef-border); }
.ef-bal-val-total { font-size: 1.05rem; font-weight: 800; letter-spacing: -.02em; }

/* SOP tip items */
.ef-tip-item {
    display: flex;
    gap: .55rem;
    padding: .55rem 0;
    border-bottom: 1px solid var(--ef-border-soft);
    font-size: .77rem;
    color: var(--ef-slate);
    line-height: 1.55;
}
.ef-tip-item:last-child { border-bottom: none; padding-bottom: 0; }
.ef-tip-dot {
    width: 5px;
    height: 5px;
    background: var(--ef-muted);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: .52rem;
}
/* Upload Zone */
.ef-upload-zone {
    border: 2px dashed var(--ef-border);
    border-radius: 8px;
    padding: 1.8rem 1rem;
    text-align: center;
    cursor: pointer;
    background: var(--ef-bg);
    transition: all .2s;
}
.ef-upload-zone:hover, .ef-upload-zone.drag-over {
    border-color: #8baed6;
    background: #f0f5fb;
}
.ef-upload-icon { font-size: 1.6rem; color: var(--ef-muted); margin-bottom: .4rem; }
.ef-upload-text { font-size: .83rem; font-weight: 600; color: var(--ef-slate); }
.ef-upload-hint { font-size: .7rem; color: var(--ef-muted); margin-top: .2rem; }
.ef-upload-name {
    margin-top: .6rem;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    background: #e8f2fd;
    color: #1a5fb4;
    border-radius: 5px;
    padding: .3rem .75rem;
    font-size: .75rem;
    font-weight: 600;
}
.ef-file-hidden { display: none; }
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="ef-page-header">
    <div>
        <div class="ef-breadcrumb">
            <a href="{{ route('finance.transactions.index') }}">Buku Kas</a>
            <span class="sep">/</span>
            <span>Catat Transaksi Baru</span>
        </div>
        <h1 class="ef-page-title">Catat Transaksi Baru</h1>
    </div>
    <a href="{{ route('finance.transactions.index') }}" class="ef-btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if($errors->any())
<div style="background:#fff8f8;border:1px solid #f5c6cb;border-radius:8px;padding:.8rem 1.1rem;margin-bottom:1.25rem;font-size:.8rem;color:var(--ef-kredit-text)">
    <i class="bi bi-exclamation-triangle-fill me-2"></i>
    <strong>Terdapat kesalahan pada formulir:</strong>
    <ul class="mb-0 mt-1 ps-4" style="line-height:1.8">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="row g-4">

    {{-- ── FORMULIR UTAMA ──────────────────────────────── --}}
    <div class="col-lg-8">
        <div class="ef-card">
            <div class="ef-card-header">
                <i class="bi bi-file-earmark-text ef-card-icon"></i>
                <p class="ef-card-title-text">Formulir Pencatatan Transaksi</p>
            </div>
            <div class="ef-card-body">
                <form action="{{ route('finance.transactions.store') }}" method="POST" id="trxForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Tipe Transaksi --}}
                    <div class="ef-field">
                        <span class="ef-label">Tipe Transaksi <span class="req">*</span></span>
                        <div class="ef-type-grid">
                            <input type="radio" name="transaction_type" id="type_debit" value="debit"
                                   class="ef-type-radio"
                                   {{ old('transaction_type','debit') === 'debit' ? 'checked':'' }}>
                            <label for="type_debit" class="ef-type-card">
                                <div class="ef-type-indicator">
                                    <i class="bi bi-arrow-down-left-circle"></i>
                                </div>
                                <div class="ef-type-text-wrap">
                                    <span class="ef-type-main">Debit</span>
                                    <span class="ef-type-sub">Uang Masuk / Penerimaan</span>
                                </div>
                                <i class="bi bi-check-circle-fill ef-type-check"></i>
                            </label>

                            <input type="radio" name="transaction_type" id="type_kredit" value="kredit"
                                   class="ef-type-radio"
                                   {{ old('transaction_type') === 'kredit' ? 'checked':'' }}>
                            <label for="type_kredit" class="ef-type-card">
                                <div class="ef-type-indicator">
                                    <i class="bi bi-arrow-up-right-circle"></i>
                                </div>
                                <div class="ef-type-text-wrap">
                                    <span class="ef-type-main">Kredit</span>
                                    <span class="ef-type-sub">Uang Keluar / Pembayaran</span>
                                </div>
                                <i class="bi bi-check-circle-fill ef-type-check"></i>
                            </label>
                        </div>
                    </div>

                    {{-- Tanggal & Akun --}}
                    <div class="row g-3 mb-0">
                        <div class="col-md-5 ef-field">
                            <label class="ef-label" for="transaction_date">Tanggal Transaksi <span class="req">*</span></label>
                            <input type="date" name="transaction_date" id="transaction_date"
                                   class="ef-input {{ $errors->has('transaction_date') ? 'ef-error' : '' }}"
                                   value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            @error('transaction_date')<p class="ef-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-md-7 ef-field">
                            <label class="ef-label" for="account_id">Akun / Kategori <span style="font-size:.68rem;font-weight:400;color:var(--ef-muted)">(CoA)</span> <span class="req">*</span></label>
                            <select name="account_id" id="account_id"
                                    class="ef-select {{ $errors->has('account_id') ? 'ef-error' : '' }}" required>
                                <option value="">— Pilih Kategori Akun —</option>
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
                            @error('account_id')<p class="ef-field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Nominal --}}
                    <div class="ef-field">
                        <label class="ef-label" for="amount">Nominal <span class="req">*</span></label>
                        <div class="ef-input-prefix-group">
                            <span class="ef-prefix-label">Rp</span>
                            <input type="number" name="amount" id="amount"
                                   class="ef-input {{ $errors->has('amount') ? 'ef-error' : '' }}"
                                   value="{{ old('amount') }}" placeholder="0"
                                   min="0" step="any" required
                                   oninput="updateAmountPreview(this.value)">
                        </div>
                        <div class="ef-amount-display">
                            <span class="ef-amount-display-label">Terbilang</span>
                            <span class="ef-amount-display-value" id="amountPreview">Rp 0</span>
                        </div>
                        @error('amount')<p class="ef-field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="ef-field">
                        <label class="ef-label" for="description">Deskripsi / Keterangan <span class="req">*</span></label>
                        <textarea name="description" id="description"
                                  class="ef-textarea {{ $errors->has('description') ? 'ef-error' : '' }}"
                                  placeholder="Contoh: Penerimaan pembayaran tagihan dari PT. XYZ untuk periode Maret 2026" required>{{ old('description') }}</textarea>
                        <p class="ef-field-hint">Tulis deskripsi sejelas dan selengkap mungkin untuk kebutuhan audit trail.</p>
                        @error('description')<p class="ef-field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Entitas --}}
                    <hr class="ef-divider">
                    <p class="ef-section-heading"><i class="bi bi-people me-1" style="font-size:.8rem"></i> Entitas Terkait <span style="text-transform:none;font-weight:400;letter-spacing:0;font-size:.68rem;color:var(--ef-muted)">(Opsional)</span></p>
                    <div class="row g-3">
                        <div class="col-md-6 ef-field">
                            <label class="ef-label" for="sender_entity_id">Entitas Pengirim <span class="opt">(Dari)</span></label>
                            <select name="sender_entity_id" id="sender_entity_id" class="ef-select">
                                <option value="">— Tidak Ada —</option>
                                @foreach($entities as $ent)
                                    <option value="{{ $ent->id }}" {{ old('sender_entity_id') == $ent->id ? 'selected':'' }}>
                                        {{ $ent->name }} ({{ ucfirst($ent->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 ef-field">
                            <label class="ef-label" for="receiver_entity_id">Entitas Penerima <span class="opt">(Ke)</span></label>
                            <select name="receiver_entity_id" id="receiver_entity_id" class="ef-select">
                                <option value="">— Tidak Ada —</option>
                                @foreach($entities as $ent)
                                    <option value="{{ $ent->id }}" {{ old('receiver_entity_id') == $ent->id ? 'selected':'' }}>
                                        {{ $ent->name }} ({{ ucfirst($ent->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ── BLOK PAJAK CORETAX ──────────────────────── --}}
                    <hr class="ef-divider">
                    <p class="ef-section-heading"><i class="bi bi-receipt-cutoff me-1" style="font-size:.8rem"></i> Informasi Pajak <span style="text-transform:none;font-weight:400;letter-spacing:0;font-size:.68rem;color:var(--ef-muted)">(Coretax – Opsional)</span></p>

                    <div class="row g-3 mb-0">
                        <div class="col-md-4 ef-field">
                            <label class="ef-label" for="dpp_amount">DPP <span class="opt">(Dasar Pengenaan Pajak)</span></label>
                            <div class="ef-input-prefix-group">
                                <span class="ef-prefix-label">Rp</span>
                                <input type="number" name="dpp_amount" id="dpp_amount"
                                       class="ef-input {{ $errors->has('dpp_amount') ? 'ef-error' : '' }}"
                                       value="{{ old('dpp_amount') }}" placeholder="0" min="0" step="any"
                                       oninput="recalcTax()">
                            </div>
                            <p class="ef-field-hint">Nominal sebelum pajak (Base Amount).</p>
                            @error('dpp_amount')<p class="ef-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-md-4 ef-field">
                            <label class="ef-label" for="tax_type">Jenis Pajak</label>
                            <select name="tax_type" id="tax_type" class="ef-select {{ $errors->has('tax_type') ? 'ef-error' : '' }}" onchange="recalcTax()">
                                <option value="none" {{ old('tax_type','none') === 'none' ? 'selected':'' }}>— Tidak Ada Pajak —</option>
                                <option value="ppn" {{ old('tax_type') === 'ppn' ? 'selected':'' }}>PPN (Saat ini 11%)</option>
                                <option value="pph_21" {{ old('tax_type') === 'pph_21' ? 'selected':'' }}>PPh 21 (Penghasilan Karyawan)</option>
                                <option value="pph_23" {{ old('tax_type') === 'pph_23' ? 'selected':'' }}>PPh 23 (Jasa / Royalti)</option>
                                <option value="pph_4_ayat_2" {{ old('tax_type') === 'pph_4_ayat_2' ? 'selected':'' }}>PPh 4 Ayat 2 (Sewa Bangunan)</option>
                            </select>
                            @error('tax_type')<p class="ef-field-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="col-md-4 ef-field">
                            <label class="ef-label" for="tax_amount">Nominal Pajak <span class="opt">(Auto / Manual)</span></label>
                            <div class="ef-input-prefix-group">
                                <span class="ef-prefix-label">Rp</span>
                                <input type="number" name="tax_amount" id="tax_amount"
                                       class="ef-input {{ $errors->has('tax_amount') ? 'ef-error' : '' }}"
                                       value="{{ old('tax_amount') }}" placeholder="Dihitung otomatis" min="0" step="any">
                            </div>
                            <p class="ef-field-hint">Nilai otomatis dihitung, boleh diedit manual jika ada selisih pembulatan.</p>
                            @error('tax_amount')<p class="ef-field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- ── BLOK UPLOAD DOKUMEN ─────────────────────── --}}
                    <hr class="ef-divider">
                    <p class="ef-section-heading"><i class="bi bi-paperclip me-1" style="font-size:.8rem"></i> Lampiran Dokumen <span style="text-transform:none;font-weight:400;letter-spacing:0;font-size:.68rem;color:var(--ef-muted)">(Bukti Transfer / Faktur – Opsional)</span></p>

                    <div class="ef-field">
                        <label class="ef-label" for="document">Upload Bukti / Faktur</label>
                        <div class="ef-upload-zone" id="uploadZone" onclick="document.getElementById('document').click()">
                            <div class="ef-upload-icon"><i class="bi bi-cloud-upload"></i></div>
                            <div class="ef-upload-text">Klik untuk pilih file atau seret ke sini</div>
                            <div class="ef-upload-hint">Format: PDF, JPG, PNG — Maks. 5 MB</div>
                            <div class="ef-upload-name" id="uploadName" style="display:none"></div>
                        </div>
                        <input type="file" name="document" id="document" class="ef-file-hidden {{ $errors->has('document') ? 'ef-error' : '' }}"
                               accept=".pdf,.jpg,.jpeg,.png" onchange="showFileName(this)">
                        @error('document')<p class="ef-field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Penanda Periode --}}
                    <hr class="ef-divider">
                    <p class="ef-section-heading"><i class="bi bi-calendar3 me-1" style="font-size:.8rem"></i> Penanda Periode Pembukuan <span style="text-transform:none;font-weight:400;letter-spacing:0;font-size:.68rem;color:var(--ef-muted)">(Opsional)</span></p>
                    <div class="ef-check-row">
                        <div class="ef-check-wrap">
                            <input type="checkbox" id="is_end_of_month" name="is_end_of_month" value="1" {{ old('is_end_of_month') ? 'checked':'' }}>
                            <label for="is_end_of_month">Tandai sebagai Akhir Bulan</label>
                        </div>
                        <div class="ef-check-wrap">
                            <input type="checkbox" id="is_end_of_year" name="is_end_of_year" value="1" {{ old('is_end_of_year') ? 'checked':'' }}>
                            <label for="is_end_of_year">Tandai sebagai Akhir Tahun</label>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="ef-action-row">
                        <a href="{{ route('finance.transactions.index') }}" class="ef-btn ef-btn-secondary">Batal</a>
                        <button type="submit" class="ef-btn ef-btn-primary">
                            <i class="bi bi-check2-circle"></i> Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── SIDEBAR ──────────────────────────────────────── --}}
    <div class="col-lg-4">

        @php
            $totalDebit  = \App\Models\FinancialTransaction::where('transaction_type','debit')->sum('amount');
            $totalKredit = \App\Models\FinancialTransaction::where('transaction_type','kredit')->sum('amount');
            $latestBal   = \App\Models\FinancialTransaction::orderByDesc('transaction_date')->orderByDesc('id')->value('running_balance') ?? 0;
        @endphp

        {{-- Ringkasan Saldo --}}
        <div class="ef-sidebar-card">
            <div class="ef-sidebar-header">
                <i class="bi bi-bar-chart-line" style="font-size:.82rem;color:var(--ef-muted)"></i>
                <p class="ef-sidebar-heading">Ringkasan Buku Kas</p>
            </div>
            <div class="ef-sidebar-body">
                <div class="ef-bal-row">
                    <span class="ef-bal-key">Total Debit</span>
                    <span class="ef-bal-val green">Rp {{ number_format($totalDebit,0,',','.') }}</span>
                </div>
                <div class="ef-bal-row">
                    <span class="ef-bal-key">Total Kredit</span>
                    <span class="ef-bal-val red">Rp {{ number_format($totalKredit,0,',','.') }}</span>
                </div>
                <div class="ef-bal-row ef-bal-row-total">
                    <span class="ef-bal-key" style="color:var(--ef-navy);font-weight:700">Saldo Berjalan</span>
                    <span class="ef-bal-val ef-bal-val-total {{ $latestBal < 0 ? 'red' : '' }}">
                        {{ $latestBal < 0 ? '−' : '' }}Rp {{ number_format(abs($latestBal),0,',','.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- SOP --}}
        <div class="ef-sidebar-card">
            <div class="ef-sidebar-header">
                <i class="bi bi-journal-text" style="font-size:.82rem;color:var(--ef-muted)"></i>
                <p class="ef-sidebar-heading">SOP Formulir Transaksi</p>
            </div>
            <div class="ef-sidebar-body">
                <div class="ef-tip-item">
                    <div class="ef-tip-dot"></div>
                    <span>Gunakan <strong>Debit</strong> untuk setiap kas yang masuk ke rekening perusahaan, seperti penerimaan pembayaran, pendapatan jasa, atau setoran modal.</span>
                </div>
                <div class="ef-tip-item">
                    <div class="ef-tip-dot"></div>
                    <span>Gunakan <strong>Kredit</strong> untuk setiap kas yang keluar, seperti pembayaran vendor, biaya operasional, atau pengeluaran rutin lainnya.</span>
                </div>
                <div class="ef-tip-item">
                    <div class="ef-tip-dot"></div>
                    <span>Pilih <strong>Akun CoA</strong> yang sesuai klasifikasinya agar laporan keuangan dan jurnal dapat digenerate secara otomatis dan akurat.</span>
                </div>
                <div class="ef-tip-item">
                    <div class="ef-tip-dot"></div>
                    <span>Isi kolom <strong>Deskripsi</strong> secara rinci dan spesifik. Informasi ini akan muncul pada laporan Buku Kas dan diperlukan dalam proses rekonsiliasi atau audit.</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function updateAmountPreview(val) {
    const preview = document.getElementById('amountPreview');
    const num = parseFloat(val) || 0;
    preview.textContent = 'Rp ' + num.toLocaleString('id-ID', { minimumFractionDigits: 0 });

    const checked = document.querySelector('input[name="transaction_type"]:checked');
    preview.className = 'ef-amount-display-value ' + (checked ? checked.value : '');
}

document.querySelectorAll('input[name="transaction_type"]').forEach(r => {
    r.addEventListener('change', () => updateAmountPreview(document.getElementById('amount').value));
});

/* ── Tax Auto Calculator ─────────────────────── */
const TAX_RATES = { ppn: 0.11, pph_21: 0.05, pph_23: 0.02, pph_4_ayat_2: 0.1 };
// PPh types are withholding (deducted from total)
const DEDUCTION_TYPES = ['pph_21', 'pph_23', 'pph_4_ayat_2'];

function recalcTax() {
    const dpp   = parseFloat(document.getElementById('dpp_amount').value) || 0;
    const type  = document.getElementById('tax_type').value;
    const taxEl = document.getElementById('tax_amount');
    const amtEl = document.getElementById('amount');

    if (type === 'none' || dpp === 0) {
        taxEl.placeholder = 'Tidak ada pajak';
        // Sync amount with whatever is in amount field if no tax
        return;
    }

    const rate = TAX_RATES[type] || 0;
    const tax  = Math.round(dpp * rate);

    // Auto-fill tax amount (keep editable)
    taxEl.value = tax;

    // Auto-fill total amount field
    const totalAmt = DEDUCTION_TYPES.includes(type)
        ? dpp - tax   // Withholding: total = DPP - pajak
        : dpp + tax;  // PPN: total = DPP + pajak

    amtEl.value = totalAmt;
    updateAmountPreview(totalAmt);
}

/* ── File Upload Display ─────────────────────── */
function showFileName(input) {
    const nameEl = document.getElementById('uploadName');
    const zone   = document.getElementById('uploadZone');
    if (input.files && input.files[0]) {
        nameEl.innerHTML = '<i class="bi bi-file-earmark-check"></i> ' + input.files[0].name;
        nameEl.style.display = 'inline-flex';
        zone.style.borderColor = '#2d6a4f';
        zone.style.background  = '#eef7f2';
    }
}

// Drag-and-drop support
const zone = document.getElementById('uploadZone');
if (zone) {
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('drag-over');
        const fileInput = document.getElementById('document');
        fileInput.files = e.dataTransfer.files;
        showFileName(fileInput);
    });
}

window.addEventListener('DOMContentLoaded', () => {
    const amt = document.getElementById('amount').value;
    updateAmountPreview(amt || '0');
});
</script>
@endpush
