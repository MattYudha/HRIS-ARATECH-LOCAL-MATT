@extends('layouts.dashboard')

@section('content')

<div class="page-heading">
    <div class="row">
        <div class="col-md-6">
            <h3>Monthly Performance Recap</h3>
        </div>
        <div class="col-md-6 text-right">
            <form method="GET" class="form-inline" style="float: right;">
                <div class="form-group mr-3">
                    <label>Period: </label>
                    <input type="month" name="period" value="{{ $period }}" class="form-control ml-2" onchange="this.form.submit()">
                </div>
                <a href="{{ route('reports.export-csv') }}?period={{ $period }}" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </form>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Summary Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Total Employees</h6>
                        <h2 class="mb-0">{{ count($kpiData) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Average Score</h6>
                        <h2 class="mb-0">{{ round(array_sum(array_column($kpiData, 'composite_score')) / (count($kpiData) ?: 1), 2) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">Excellent</h6>
                        <h2 class="mb-0">{{ count(array_filter($kpiData, fn($e) => $e['performance_level'] === 'excellent')) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Below Target</h6>
                        <h2 class="mb-0">{{ count(array_filter($kpiData, fn($e) => $e['performance_level'] === 'needs_improvement' || $e['performance_level'] === 'unsatisfactory')) }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Performance Ranking - {{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="performanceTable">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>Employee</th>
                                        <th>Department</th>
                                        <th>Score</th>
                                        <th>Performance</th>
                                        <th>Achieved</th>
                                        <th>Warning</th>
                                        <th>Critical</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kpiData as $index => $data)
                                    <tr>
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>
                                            <strong>{{ $data['employee']->fullname }}</strong><br>
                                            <small class="text-muted">{{ $data['employee']->role?->title }}</small>
                                        </td>
                                        <td>{{ $data['employee']->department->name }}</td>
                                        <td>
                                            <h5 class="mb-0">
                                                <span class="badge badge-{{ 
                                                    $data['composite_score'] >= 90 ? 'success' : 
                                                    ($data['composite_score'] >= 75 ? 'info' : 
                                                    ($data['composite_score'] >= 60 ? 'warning' : 'danger'))
                                                }}">
                                                    {{ round($data['composite_score'], 2) }}/100
                                                </span>
                                            </h5>
                                        </td>
                                        <td>
                                            @switch($data['performance_level'])
                                                @case('excellent')
                                                    <span class="badge badge-success">Excellent</span>
                                                    @break
                                                @case('good')
                                                    <span class="badge badge-info">Good</span>
                                                    @break
                                                @case('satisfactory')
                                                    <span class="badge badge-warning">Satisfactory</span>
                                                    @break
                                                @case('needs_improvement')
                                                    <span class="badge badge-warning">Needs Improvement</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-danger">Unsatisfactory</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="badge badge-success">{{ $data['achievements'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning">{{ $data['warnings'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">{{ $data['critical'] }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('kpi.show', $data['employee']->id) }}?period={{ $period }}" 
                                               class="btn btn-sm btn-info" title="View Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('reports.export-pdf', $data['employee']->id) }}?period={{ $period }}" 
                                               class="btn btn-sm btn-primary" target="_blank" title="Export PDF">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">
                                            No KPI data available for this period
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Color Legend -->
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Performance Level Guide:</h6>
                        <div class="row">
                            <div class="col-md-2">
                                <span class="badge badge-success" style="padding: 8px;">Excellent (90-100)</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-info" style="padding: 8px;">Good (75-89)</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-warning" style="padding: 8px;">Satisfactory (60-74)</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-warning" style="padding: 8px;">Needs Improvement (45-59)</span>
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-danger" style="padding: 8px;">Unsatisfactory (<45)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Optional: Add DataTables for sorting and filtering
    $(document).ready(function() {
        $('#performanceTable').DataTable({
            pageLength: 25,
            ordering: true,
            searching: true,
            columnDefs: [
                { orderable: false, targets: -1 }
            ]
        });
    });
</script>
@endsection
