@extends('layouts.dashboard')

@section('title', 'Ajukan Klaim Biaya')

@push('styles')
<style>
.form-hero {
    background: linear-gradient(135deg,#1a1f3c 0%,#2d3561 100%);
    border-radius: 16px; padding: 1.2rem 1.6rem; margin-bottom: 1.25rem;
}
.form-hero .fh-title { font-size:1rem; font-weight:800; color:#fff; margin:0; }
.form-hero .fh-sub   { font-size:.74rem; color:rgba(255,255,255,.5); margin:.2rem 0 0; }

.fin-label { font-size:.78rem; font-weight:700; color:#344767; margin-bottom:.4rem; display:block; }
.fin-input {
    border-radius:10px; border:1.5px solid #e4e8f0; font-size:.85rem;
    padding:.6rem .9rem; transition:border-color .15s, box-shadow .15s; width:100%;
}
.fin-input:focus { border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12); outline:none; }

.cat-card-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(140px, 1fr)); gap:.6rem; }
.cat-card-opt { display:none; }
.cat-card-lbl {
    display:flex; flex-direction:column; align-items:center; gap:.3rem;
    border:2px solid #e4e8f0; border-radius:11px; padding:.9rem .5rem;
    cursor:pointer; transition:all .15s; text-align:center; background:#fff;
}
.cat-card-lbl .cc-icon { font-size:1.6rem; }
.cat-card-lbl .cc-name { font-size:.78rem; font-weight:800; color:#344767; }
.cat-card-opt:checked + .cat-card-lbl { border-color:#5e72e4; background:#f0f2ff; box-shadow:0 0 0 3px rgba(94,114,228,.12); }
</style>
@endpush

@section('content')

<div class="form-hero shadow">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p class="fh-title">➕ Ajukan Klaim Biaya Baru</p>
            <p class="fh-sub">Lengkapi formulir di bawah untuk mengajukan reimbursement biaya operasional</p>
        </div>
        <a href="{{ route('finance.claims.index') }}" class="btn btn-sm mb-0"
           style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
            ← Kembali
        </a>
    </div>
</div>

@if($errors->any())
    <div class="alert mb-3 text-white py-2" style="background:#f5365c;border-radius:10px;font-size:.84rem">
        <i class="bi bi-exclamation-circle-fill me-1"></i>
        <span>Mohon periksa kembali inputan Anda.</span>
    </div>
@endif

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-body p-4">
                <form action="{{ route('finance.claims.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="fin-label">Kategori Klaim <span class="text-danger">*</span></label>
                        <div class="cat-card-grid">
                            @php
                                $categories = [
                                    'transport'   => ['🚗', 'Transport'],
                                    'meals'       => ['🍱', 'Makan'],
                                    'operational' => ['⚙️', 'Operasional'],
                                    'equipment'   => ['📦', 'Peralatan'],
                                    'other'       => ['❓', 'Lainnya'],
                                ];
                            @endphp
                            @foreach($categories as $slug => $data)
                            <div class="cat-item">
                                <input type="radio" name="category" id="cat_{{ $slug }}" value="{{ $slug }}" class="cat-card-opt" required {{ old('category') == $slug ? 'checked' : '' }}>
                                <label for="cat_{{ $slug }}" class="cat-card-lbl w-100">
                                    <span class="cc-icon">{{ $data[0] }}</span>
                                    <span class="cc-name">{{ $data[1] }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="fin-label" for="title">Judul Klaim <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="fin-input @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Transport Meeting Klien" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="fin-label" for="amount">Nominal (Rp) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="fin-input @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="0" required>
                            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fin-label" for="account_id">Akun Biaya <span class="text-danger">*</span></label>
                        <select name="account_id" id="account_id" class="fin-input @error('account_id') is-invalid @enderror" required>
                            <option value="">— Pilih Akun Biaya —</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}" {{ old('account_id') == $acc->id ? 'selected' : '' }}>
                                    [{{ $acc->code }}] {{ $acc->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-muted mt-1">Pilih kategori biaya yang paling sesuai dengan pengajuan Anda.</p>
                        @error('account_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="fin-label" for="description">Deskripsi Lengkap</label>
                        <textarea name="description" id="description" rows="3" class="fin-input" placeholder="Jelaskan detail pengeluaran...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="fin-label" for="attachment">Bukti Pendukung (Foto Struk/PDF) <span class="text-danger">*</span></label>
                        <input type="file" name="attachment" id="attachment" class="fin-input" accept="image/*,.pdf" required>
                        <p class="text-xs text-muted mt-1">Upload foto kuitansi atau struk asli. Max 5MB.</p>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('finance.claims.index') }}" class="btn btn-outline-secondary mb-0" style="border-radius:9px">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0 px-4" style="border-radius:9px">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
