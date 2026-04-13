@extends('layouts.dashboard')

@section('title', 'Edit Entitas Keuangan')

@section('content')
<div class="row">
    <div class="col-8 mx-auto">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Edit Entitas Keuangan: {{ $entity->name }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('finance.entities.update', $entity->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Entitas</label>
                        <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                            <option value="internal" {{ old('type', $entity->type) == 'internal' ? 'selected' : '' }}>Internal (Kas Perusahaan)</option>
                            <option value="bank" {{ old('type', $entity->type) == 'bank' ? 'selected' : '' }}>Bank (Rekening Bank)</option>
                            <option value="vendor" {{ old('type', $entity->type) == 'vendor' ? 'selected' : '' }}>Vendor (Supplier)</option>
                            <option value="client" {{ old('type', $entity->type) == 'client' ? 'selected' : '' }}>Client (Pelanggan)</option>
                            <option value="other" {{ old('type', $entity->type) == 'other' ? 'selected' : '' }}>Other (Lainnya)</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Entitas</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $entity->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="contact_info" class="form-label">Info Kontak (Opsional)</label>
                        <input type="text" name="contact_info" id="contact_info" class="form-control @error('contact_info') is-invalid @enderror" value="{{ old('contact_info', $entity->contact_info) }}">
                        @error('contact_info')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $entity->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('finance.entities.index') }}" class="btn btn-secondary mb-0">Batal</a>
                        <button type="submit" class="btn btn-primary mb-0">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
