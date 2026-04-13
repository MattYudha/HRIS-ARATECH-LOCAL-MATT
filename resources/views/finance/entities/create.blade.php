@extends('layouts.dashboard')

@section('title', 'Tambah Entitas Keuangan')

@push('styles')
<style>
.form-hero {
    background: linear-gradient(135deg,#1a1f3c 0%,#2d3561 100%);
    border-radius: 16px; padding: 1.2rem 1.6rem; margin-bottom: 1.25rem;
}
.form-hero .fh-title { font-size:1rem; font-weight:800; color:#fff; margin:0; }
.form-hero .fh-sub   { font-size:.74rem; color:rgba(255,255,255,.5); margin:.2rem 0 0; }

.fin-form-card { border-radius:14px; border:none; overflow:hidden; }
.fin-form-card .card-body { padding:1.6rem 2rem; }

.form-section-title {
    font-size:.68rem; font-weight:800; letter-spacing:.09em; text-transform:uppercase;
    color:#8392ab; border-left:3px solid #5e72e4; padding-left:.65rem; margin-bottom:1rem;
}
.fin-label { font-size:.78rem; font-weight:700; color:#344767; margin-bottom:.4rem; }
.fin-input {
    border-radius:10px; border:1.5px solid #e4e8f0; font-size:.85rem;
    padding:.6rem .9rem; transition:border-color .15s, box-shadow .15s;
}
.fin-input:focus {
    border-color:#5e72e4; box-shadow:0 0 0 3px rgba(94,114,228,.12); outline:none;
}
.fin-input.is-invalid { border-color:#f5365c; }

/* Type selector cards */
.type-card-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(130px,1fr)); gap:.6rem; }
.type-card-option { display:none; }
.type-card-label {
    display:flex; flex-direction:column; align-items:center; gap:.35rem;
    border:2px solid #e4e8f0; border-radius:11px; padding:.8rem .5rem;
    cursor:pointer; transition:all .15s; text-align:center; background:#fff;
}
.type-card-label .tc-icon { font-size:1.5rem; }
.type-card-label .tc-name { font-size:.73rem; font-weight:700; color:#344767; }
.type-card-label .tc-desc { font-size:.65rem; color:#8392ab; }
.type-card-option:checked + .type-card-label {
    border-color:#5e72e4; background:#f0f2ff; box-shadow:0 0 0 3px rgba(94,114,228,.12);
}
.type-card-label:hover { border-color:#aab4e8; background:#f8f9ff; }
</style>
@endpush

@section('content')

<div class="form-hero shadow">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <p class="fh-title">➕ Tambah Entitas Keuangan Baru</p>
            <p class="fh-sub">Daftarkan bank, vendor, atau entitas internal ke dalam sistem</p>
        </div>
        <a href="{{ route('finance.entities.index') }}" class="btn btn-sm mb-0"
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
        <div class="card fin-form-card shadow-sm">
            <div class="card-body">
                <form action="{{ route('finance.entities.store') }}" method="POST">
                    @csrf

                    {{-- Tipe Entitas --}}
                    <p class="form-section-title">Tipe Entitas</p>
                    <div class="type-card-grid mb-4">
                        @php
                            $types = [
                                'bank'       => ['🏦','Bank','Rekening bank'],
                                'vendor'     => ['🏪','Vendor','Supplier barang/jasa'],
                                'internal'   => ['🏢','Internal','Kas perusahaan'],
                                'client'     => ['🤝','Client','Pelanggan/klien'],
                                'employee'   => ['👤','Karyawan','Pegawai internal'],
                                'tax_office' => ['🏛️','Pajak','Kantor pajak'],
                                'other'      => ['📌','Lainnya','Entitas lain'],
                            ];
                        @endphp
                        @foreach($types as $val => [$icon, $name, $desc])
                        <div>
                            <input type="radio" name="type" id="type_{{ $val }}" value="{{ $val }}"
                                   class="type-card-option" {{ old('type') == $val ? 'checked':'' }} required>
                            <label for="type_{{ $val }}" class="type-card-label w-100">
                                <span class="tc-icon">{{ $icon }}</span>
                                <span class="tc-name">{{ $name }}</span>
                                <span class="tc-desc">{{ $desc }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>

                    {{-- Info Dasar --}}
                    <p class="form-section-title">Informasi Entitas</p>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label class="fin-label" for="name">Nama Entitas <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   class="form-control fin-input @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Contoh: BCA Pusat, PT Global Vendor, Kas Umum Kantor..." required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="fin-label" for="description">Deskripsi <span class="text-muted fw-normal">(opsional)</span></label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control fin-input @error('description') is-invalid @enderror"
                                      placeholder="Catatan tambahan tentang entitas ini...">{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('finance.entities.index') }}" class="btn btn-outline-secondary mb-0" style="border-radius:9px">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0" style="border-radius:9px;padding:.5rem 1.6rem">
                            <i class="bi bi-save me-1"></i>Simpan Entitas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
