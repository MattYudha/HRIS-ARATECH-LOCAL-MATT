@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Incident Management</h3>
                <p class="text-subtitle text-muted">Track employee sanctions (SP) and awards</p>
            </div>
            <div class="col-md-6 text-md-end">
                @can('create', App\Models\Incident::class)
                    <a href="{{ route('incidents.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Record New Incident</a>
                @endcan
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Type</th>
                                <th>Severity</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($incidents as $incident)
                                <tr>
                                    <td>{{ $incident->incident_date->format('d M Y') }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $incident->employee->fullname ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $incident->employee->department->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $typeBadge = match(strtolower($incident->type)) {
                                                'sp1', 'sp2', 'sp3', 'peringatan' => 'bg-danger',
                                                'penghargaan', 'award', 'prestasi' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $typeBadge }}">{{ strtoupper($incident->type) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $sevBadge = match($incident->severity) {
                                                'critical' => 'bg-danger',
                                                'high' => 'bg-warning text-dark',
                                                'medium' => 'bg-info',
                                                default => 'bg-light text-dark'
                                            };
                                        @endphp
                                        <span class="badge {{ $sevBadge }}">{{ ucfirst($incident->severity) }}</span>
                                    </td>
                                    <td>{{ Str::limit($incident->description, 50) }}</td>
                                    <td>
                                        <span class="text-capitalize">{{ $incident->status }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @can('view', $incident)
                                                <a href="{{ route('incidents.show', $incident->id) }}" class="btn btn-outline-info"><i class="bi bi-eye"></i></a>
                                            @endcan
                                            @can('update', $incident)
                                                <a href="{{ route('incidents.edit', $incident->id) }}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $incidents->links() }}
            </div>
        </div>
    </section>
</div>
@endsection
