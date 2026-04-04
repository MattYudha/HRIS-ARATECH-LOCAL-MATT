@extends('layouts.dashboard')

@section('content')

<div class="page-heading">
    <h3>Executive KPI Dashboard</h3>
    <p class="text-muted">Period: {{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') }}</p>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Key Metrics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Total Employees</h6>
                        <h2 class="mb-0">{{ $totalEmployees }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Excellent</h6>
                        <h2 class="mb-0">{{ $excellentCount }}<span class="text-sm"> / {{ $totalEmployees }}</span></h2>
                        <small class="text-muted">{{ $totalEmployees > 0 ? round(($excellentCount / $totalEmployees) * 100, 1) : 0 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">Good Performance</h6>
                        <h2 class="mb-0">{{ $goodCount }}<span class="text-sm"> / {{ $totalEmployees }}</span></h2>
                        <small class="text-muted">{{ $totalEmployees > 0 ? round(($goodCount / $totalEmployees) * 100, 1) : 0 }}%</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-danger">
                    <div class="card-body">
                        <h6 class="text-danger font-weight-bold mb-1">Unresolved Incidents</h6>
                        <h2 class="mb-0">{{ $recentIncidents->count() }}</h2>
                        <small class="text-muted">Require Attention</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top Performers -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Top 5 Performers</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($topPerformers as $index => $record)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $index + 1 }}. {{ $record->employee->fullname }}
                                        </h6>
                                        <small class="text-muted">{{ $record->employee->department->name }} • {{ $record->employee->role?->title }}</small>
                                    </div>
                                    <span class="badge badge-success" style="height: fit-content;">{{ round($record->composite_score, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 5px; margin-top: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ $record->composite_score }}%;"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center py-3">No data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Performers -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Bottom 5 Performers (Development Focus)</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @forelse($bottomPerformers as $index => $record)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <div>
                                        <h6 class="mb-1">
                                            {{ $index + 1 }}. {{ $record->employee->fullname }}
                                        </h6>
                                        <small class="text-muted">{{ $record->employee->department->name }} • {{ $record->employee->role?->title }}</small>
                                    </div>
                                    <span class="badge badge-warning" style="height: fit-content;">{{ round($record->composite_score, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 5px; margin-top: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $record->composite_score }}%;"></div>
                                </div>
                            </div>
                            @empty
                            <p class="text-muted text-center py-3">No data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Performance -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Department Performance Comparison</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Employees</th>
                                        <th>Avg Score</th>
                                        <th>Score Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departments as $dept)
                                    <tr>
                                        <td><strong>{{ $dept['name'] }}</strong></td>
                                        <td>{{ $dept['employee_count'] }}</td>
                                        <td>
                                            <span class="badge badge-{{ 
                                                $dept['avg_score'] >= 90 ? 'success' : 
                                                ($dept['avg_score'] >= 75 ? 'info' : 
                                                ($dept['avg_score'] >= 60 ? 'warning' : 'danger'))
                                            }}">
                                                {{ round($dept['avg_score'], 2) }}/100
                                            </span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar {{ 
                                                    $dept['avg_score'] >= 90 ? 'bg-success' : 
                                                    ($dept['avg_score'] >= 75 ? 'bg-info' : 
                                                    ($dept['avg_score'] >= 60 ? 'bg-warning' : 'bg-danger'))
                                                }}" 
                                                     style="width: {{ $dept['avg_score'] }}%;">
                                                    {{ round($dept['avg_score'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Incidents -->
        @if($recentIncidents->count() > 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="card-title mb-0">Unresolved Incidents</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentIncidents as $incident)
                                    <tr>
                                        <td><strong>{{ $incident->employee->fullname }}</strong></td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $incident->type)) }}</td>
                                        <td>{{ $incident->incident_date->format('d M Y') }}</td>
                                        <td>
                                            @switch($incident->severity)
                                                @case('low')
                                                    <span class="badge badge-info">Low</span>
                                                    @break
                                                @case('medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                    @break
                                                @case('high')
                                                    <span class="badge badge-danger">High</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-dark">Critical</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $incident->status === 'resolved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($incident->status) }}
                                            </span>
                                        </td>
                                        <td><small>{{ Str::limit($incident->description, 50) }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Export Actions -->
        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('reports.monthly-recap') }}?period={{ $period }}" class="btn btn-info">
                    <i class="fas fa-list"></i> View Monthly Recap
                </a>
                <a href="{{ route('reports.export-csv') }}?period={{ $period }}" class="btn btn-success">
                    <i class="fas fa-download"></i> Export All Data to CSV
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
