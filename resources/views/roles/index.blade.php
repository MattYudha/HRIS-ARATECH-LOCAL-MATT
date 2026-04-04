@extends('layouts.dashboard')

@section('content')

@php
    $isPowerUser = session('role') === 'Super Admin';
@endphp



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Roles</h3>
                <p class="text-subtitle text-muted">
                    Manage roles data
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ACTION -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                @unless($isPowerUser)
                                    <span class="badge bg-secondary">View only - Super Admin required to edit</span>
                                @endunless
                            </div>
                            @if($isPowerUser)
                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-1"></i> New Role
                                </a>
                            @endif
                        </div>

                        <!-- ALERT -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- TABLE -->
                        <div class="table-responsive">
                            <table class="table table-striped align-middle" id="table1">
                                <thead>
                                    <tr>
                                        <th style="width: 22%">Title</th>
                                        <th>Description</th>
                                        <th class="text-center" style="width: 200px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($roles as $role)
                                        <tr>
                                            <td class="fw-semibold">
                                                {{ $role->title }}
                                            </td>
                                            <td>
                                                {{ $role->description ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($isPowerUser)
                                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                                        <!-- EDIT INFO -->
                                                        <a
                                                            href="{{ route('roles.edit', $role->id) }}"
                                                            class="btn btn-sm btn-light-warning"
                                                            data-bs-toggle="tooltip"
                                                            title="Edit Role"
                                                        >
                                                            <i class="bi bi-pencil"></i>
                                                        </a>

                                                        <!-- EDIT AKSES -->
                                                        <a
                                                            href="{{ route('roles.edit', $role->id) }}#akses"
                                                            class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="tooltip"
                                                            title="Edit akses module untuk role ini"
                                                        >
                                                            Edit Akses
                                                        </a>

                                                        <!-- DELETE -->
                                                        <form
                                                            action="{{ route('roles.destroy', $role->id) }}"
                                                            method="POST"
                                                            class="delete-form"
                                                        >
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-light-danger"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Role"
                                                            >
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">Hanya Super Admin yang bisa edit</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#table1').DataTable();

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus role ini?');
        });
    });
</script>
@endpush
