@extends('layouts.dashboard')

@section('content')

@php
    $selectedAccess = old('access', []);
@endphp



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Create Role</h3>
                <p class="text-subtitle text-muted">
                    Add new role data
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('roles.index') }}">Roles</a>
                        </li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="section">
        <div class="row">
            <div class="col-12 col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ALERT -->
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- FORM -->
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="form-control"
                                    value="{{ old('title') }}"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea
                                    name="description"
                                    id="description"
                                    class="form-control"
                                    rows="3"
                                >{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3" id="akses">
                                <label class="form-label fw-semibold">Akses Modul</label>
                                <p class="text-muted small mb-2">Centang modul yang boleh diakses oleh role ini.</p>
                                <div class="row g-2">
                                    @foreach($modules as $module)
                                        <div class="col-md-6">
                                            <div class="form-check border rounded p-2 h-100">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="access[]"
                                                    value="{{ $module['key'] }}"
                                                    id="access_{{ $module['key'] }}"
                                                    {{ in_array($module['key'], $selectedAccess) ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="access_{{ $module['key'] }}">
                                                    {{ $module['label'] }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Save Role
                                </button>
                                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                    Back
                                </a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection
