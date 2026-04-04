@extends('layouts.dashboard')

@section('content')
@php
    $typeBadge = match (strtolower((string) $incident->type)) {
        'sp1', 'sp2', 'sp3', 'peringatan' => 'bg-danger',
        'penghargaan', 'award', 'prestasi' => 'bg-success',
        default => 'bg-secondary',
    };

    $severityBadge = match ($incident->severity) {
        'critical' => 'bg-danger',
        'high' => 'bg-warning text-dark',
        'medium' => 'bg-info text-dark',
        default => 'bg-light text-dark',
    };

    $statusBadge = match ($incident->status) {
        'resolved' => 'bg-success',
        'closed' => 'bg-dark',
        'investigating' => 'bg-warning text-dark',
        'pending' => 'bg-secondary',
        default => 'bg-light text-dark',
    };

    $employeeName = data_get($incident, 'employee.fullname', 'N/A');
    $employeeNik = data_get($incident, 'employee.nik', 'N/A');
    $employeeDepartment = data_get($incident, 'employee.department.name', 'N/A');
    $reportedBy = data_get($incident, 'reportedBy.employee.fullname') ?? data_get($incident, 'reportedBy.name', 'N/A');
    $resolvedBy = data_get($incident, 'resolvedBy.employee.fullname') ?? data_get($incident, 'resolvedBy.name', 'N/A');
@endphp

<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Incident Details</h3>
                <p class="text-subtitle text-muted mb-0">Review the full record for this incident or award.</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left me-1"></i> Back to List
                </a>
                @can('update', $incident)
                    <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil me-1"></i> Edit Record
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <span class="badge {{ $typeBadge }}">{{ strtoupper((string) $incident->type) }}</span>
                            <span class="badge {{ $severityBadge }}">{{ ucfirst((string) $incident->severity) }}</span>
                            <span class="badge {{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', (string) $incident->status)) }}</span>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Description</h5>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($incident->description)) !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="mb-2">Action Taken</h5>
                            @if (filled($incident->action_taken))
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($incident->action_taken)) !!}
                                </div>
                            @else
                                <div class="border rounded p-3 bg-light text-muted">
                                    No follow-up action has been recorded yet.
                                </div>
                            @endif
                        </div>

                        @if (filled($incident->notes))
                            <div>
                                <h5 class="mb-2">Additional Notes</h5>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($incident->notes)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Record Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Reference</small>
                            <div class="fw-bold">#{{ $incident->id }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Employee</small>
                            <div class="fw-bold">{{ $employeeName }}</div>
                            <small class="text-muted">{{ $employeeNik }} &bull; {{ $employeeDepartment }}</small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Incident Date</small>
                            <div>{{ $incident->incident_date ? $incident->incident_date->format('d M Y') : 'N/A' }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Reported By</small>
                            <div>{{ $reportedBy }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Resolved By</small>
                            <div>{{ $resolvedBy }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Resolved At</small>
                            <div>{{ $incident->resolved_at ? $incident->resolved_at->format('d M Y H:i') : 'Not resolved yet' }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Created At</small>
                            <div>{{ $incident->created_at ? $incident->created_at->format('d M Y H:i') : 'N/A' }}</div>
                        </div>

                        <div>
                            <small class="text-muted d-block">Last Updated</small>
                            <div>{{ $incident->updated_at ? $incident->updated_at->format('d M Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
