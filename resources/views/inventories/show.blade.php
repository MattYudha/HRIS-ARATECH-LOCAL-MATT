@extends('layouts.dashboard')

@section('content')

<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $inventory->name }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center">
        <h3>{{ $inventory->name }}</h3>
        <div>
            <a href="{{ route('inventories.edit', $inventory) }}" class="btn btn-primary me-2" title="Edit">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            <a href="{{ route('inventories.index') }}" class="btn btn-secondary" title="Back">
                <i class="bi bi-arrow-left-circle me-1"></i> Back
            </a>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="section">

        {{-- Summary Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Type</small>
                        <h4 class="mt-1">
                            <span class="badge {{ $inventory->item_type == 'habis_pakai' ? 'bg-info' : 'bg-primary' }}">
                                {{ $inventory->item_type == 'habis_pakai' ? 'Consumable' : 'Asset' }}
                            </span>
                        </h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Quantity</small>
                        <h4 class="mt-1">{{ $inventory->quantity }}</h4>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <small class="text-muted">Status</small>
                        <h4 class="mt-1">
                            @if ($inventory->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif ($inventory->status === 'inactive')
                                <span class="badge bg-warning text-dark">Inactive</span>
                            @else
                                <span class="badge bg-danger">Damaged</span>
                            @endif
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Information --}}
        <div class="card mb-4">
            <div class="card-header">
                <h4>Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="200">Location</th>
                        <td>{{ $inventory->location ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Area / Room</th>
                        <td>{{ $inventory->area ?? '-' }} / {{ $inventory->room ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Date</th>
                        <td>
                            {{ $inventory->purchase_date
                                ? $inventory->purchase_date->format('d M Y')
                                : 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td>{{ $inventory->description ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Usage History --}}
        <div class="card">
            <div class="card-header">
                <h4>Usage History</h4>
            </div>
            <div class="card-body">

                @if ($inventory->usageLogs->isEmpty())
                    <div class="text-center text-muted py-4">
                        No usage logs yet
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Borrowed Date</th>
                                    <th>Returned Date</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventory->usageLogs as $log)
                                    <tr>
                                        <td>{{ $log->employee->fullname }}</td>
                                        <td>{{ $log->borrowed_date->format('d M Y H:i') }}</td>
                                        <td>
                                            @if ($log->returned_date)
                                                {{ $log->returned_date->format('d M Y H:i') }}
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    Currently Borrowed
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $log->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>

    </section>
</div>

@endsection
