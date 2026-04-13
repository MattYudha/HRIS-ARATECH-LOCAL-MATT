@extends('layouts.dashboard')

@section('title', 'Edit Akun (CoA)')

@section('content')
<div class="row">
    <div class="col-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Edit Akun: {{ $account->name }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('finance.accounts.update', $account->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="category" class="form-label">Kategori Akun</label>
                        <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                            <option value="asset" {{ old('category', $account->category) == 'asset' ? 'selected' : '' }}>Harta/Aset (Asset)</option>
                            <option value="liability" {{ old('category', $account->category) == 'liability' ? 'selected' : '' }}>Kewajiban/Hutang (Liability)</option>
                            <option value="equity" {{ old('category', $account->category) == 'equity' ? 'selected' : '' }}>Modal (Equity)</option>
                            <option value="revenue" {{ old('category', $account->category) == 'revenue' ? 'selected' : '' }}>Pendapatan (Revenue)</option>
                            <option value="expense" {{ old('category', $account->category) == 'expense' ? 'selected' : '' }}>Biaya/Pengeluaran (Expense)</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="code" class="form-label">Kode Akun</label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $account->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Nama Akun</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $account->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $account->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan Akun</label>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('finance.accounts.index') }}" class="btn btn-secondary mb-0">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
