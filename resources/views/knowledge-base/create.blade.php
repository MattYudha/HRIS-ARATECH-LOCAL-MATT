@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Artikel Knowledge Base</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('knowledge-base.index') }}">Knowledge Base</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('knowledge-base.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Judul Artikel</label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label for="category" class="form-label">Kategori</label>
                                <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="user-guide">User Guide (Panduan Aplikasi)</option>
                                    <option value="company-policy">Company Policy (Kebijakan)</option>
                                    <option value="admin-guide">Admin/HR Administrator Guide</option>
                                    <option value="faq">FAQ</option>
                                </select>
                                @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="keywords" class="form-label">Keywords (pisahkan dengan spasi, untuk pencarian)</label>
                        <input type="text" name="keywords" id="keywords" class="form-control" value="{{ old('keywords') }}" placeholder="contoh: kpi target penilaian">
                    </div>

                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Konten Artikel (HTML diperbolehkan)</label>
                        <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" rows="15" required>{{ old('content') }}</textarea>
                        @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('knowledge-base.index') }}" class="btn btn-light-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
