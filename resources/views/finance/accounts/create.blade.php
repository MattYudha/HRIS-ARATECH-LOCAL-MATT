@extends('layouts.dashboard')

@section('title', 'Tambah Akun (CoA)')

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
.fin-label { font-size:.78rem; font-weight:700; color:#344767; margin-bottom:.4rem; }
.fin-input {
    border-radius:10px; border:1.5px solid #e4e8f0; font-size:.85rem;
    padding:.6rem .9rem; transition:border-color .15s, box-shadow .15s;
}
.fin-input:focus { border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12); outline:none; }
.fin-input.is-invalid { border-color:#f5365c; }

/* Category cards */
.cat-card-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:.6rem; }
@media (max-width:768px) { .cat-card-grid { grid-template-columns:repeat(2,1fr); } }
.cat-card-opt { display:none; }
.cat-card-lbl {
    display:flex; flex-direction:column; align-items:center; gap:.3rem;
    border:2px solid #e4e8f0; border-radius:11px; padding:.9rem .5rem;
    cursor:pointer; transition:all .15s; text-align:center; background:#fff;
}
.cat-card-lbl .cc-icon { font-size:1.6rem; }
.cat-card-lbl .cc-name { font-size:.78rem; font-weight:800; color:#344767; }
.cat-card-lbl .cc-sub  { font-size:.63rem; color:#8392ab; }

.cat-card-opt:checked + .cat-card-lbl.asset     { border-color:#1171ef; background:#dff0fb; }
.cat-card-opt:checked + .cat-card-lbl.liability { border-color:#d48a00; background:#fff4de; }
.cat-card-opt:checked + .cat-card-lbl.equity    { border-color:#8965e0; background:#ede8ff; }
.cat-card-opt:checked + .cat-card-lbl.revenue   { border-color:#1aae6f; background:#e2faf0; }
.cat-card-opt:checked + .cat-card-lbl.expense   { border-color:#f5365c; background:#fce8e8; }
.cat-card-lbl:hover { border-color:#aab4e8; transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.07); }

/* Code preview */
.code-preview {
    font-family:'Courier New',monospace; background:#f4f6fb;
    border:1.5px solid #e4e8f0; border-radius:10px;
    padding:.6rem 1rem; font-size:.95rem; font-weight:800;
    color:#344767; letter-spacing:.05em; text-align:center;
    min-height:42px;
}
</style>
@endpush

@section('content')

<div class="form-hero shadow">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p class="fh-title">➕ Tambah Akun CoA Baru</p>
            <p class="fh-sub">Buat klasifikasi akun baru untuk pencatatan transaksi keuangan</p>
        </div>
        <a href="{{ route('finance.accounts.index') }}" class="btn btn-sm mb-0"
           style="background:rgba(255,255,255,.15);color:#fff;border-radius:8px;font-size:.78rem;padding:.4rem 1rem;border:1px solid rgba(255,255,255,.25)">
            ← Kembali
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

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:14px">
            <div class="card-body p-4">
                <form action="{{ route('finance.accounts.store') }}" method="POST">
                    @csrf

                    {{-- Kategori --}}
                    <p class="form-section-title">Kategori Akun</p>
                    <div class="cat-card-grid mb-4">
                        @php
                            $cats = [
                                'asset'     => ['🏦','Asset','Harta & kas'],
                                'liability' => ['💳','Liability','Utang & kewajiban'],
                                'equity'    => ['🏛️','Equity','Modal & ekuitas'],
                                'revenue'   => ['📈','Revenue','Pendapatan'],
                                'expense'   => ['📉','Expense','Biaya & pengeluaran'],
                            ];
                        @endphp
                        @foreach($cats as $val => [$icon, $name, $sub])
                        <div>
                            <input type="radio" name="category" id="cat_{{ $val }}" value="{{ $val }}"
                                   class="cat-card-opt" {{ old('category') == $val ? 'checked':'' }} required>
                            <label for="cat_{{ $val }}" class="cat-card-lbl {{ $val }} w-100">
                                <span class="cc-icon">{{ $icon }}</span>
                                <span class="cc-name">{{ $name }}</span>
                                <span class="cc-sub">{{ $sub }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Kode & Nama --}}
                    <p class="form-section-title">Identitas Akun</p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="fin-label" for="code">Kode Akun <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code"
                                   class="form-control fin-input @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="Contoh: 1-1000"
                                   oninput="document.getElementById('codePreview').textContent = this.value || 'KODE'"
                                   required>
                            @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <p class="text-xs text-muted mt-1">Format bebas, misal: 101, 1-101, ACC-001</p>
                        </div>
                        <div class="col-md-8">
                            <label class="fin-label" for="name">Nama Akun <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control fin-input @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: Kas Kecil, Biaya Operasional, Modal Awal..." required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="fin-label">Preview Kode</label>
                            <div class="code-preview" id="codePreview">{{ old('code', 'KODE') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="fin-label" for="description">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control fin-input @error('description') is-invalid @enderror"
                                      placeholder="Penjelasan tentang penggunaan akun ini...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-secondary mb-0" style="border-radius:9px">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0" style="border-radius:9px;padding:.5rem 1.6rem">
                            <i class="bi bi-save me-1"></i>Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
