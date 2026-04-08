@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>Department Performance Analysis</h3>
    <p class="text-muted">{{ Auth::user()->employee->department->name }}</p>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Period Selector -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label class="form-label">Select Period</label>
                <div class="input-group">
                    <input type="month" id="periodSelect" class="form-control" value="{{ $period }}" onchange="changePeriod()">
                </div>
            </div>
            <div class="col-md-9">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    Department summary for <strong>{{ count($deptEmployees) }} employees</strong> in <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') }}</strong>
                </div>
            </div>
        </div>

        <!-- Department Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Total Employees</h6>
                        <h2 class="mb-0">{{ count($deptEmployees) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Department Average</h6>
                        <h2 class="mb-0">{{ round($avgScore, 2) }}/100</h2>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">Highest Performer</h6>
                        @php
                            $highest = $deptKPIs->sortByDesc('composite_score')->first();
                        @endphp
                        <h4 class="mb-0">{{ round($highest['composite_score'] ?? 0, 2) }}</h4>
                        <small class="text-muted">{{ $highest['employee']->fullname ?? 'N/A' }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Performance Trend</h6>
                        <h4 class="mb-0">
                            @php
                                $excellent = collect($deptKPIs)->where('performance_level', 'excellent')->count();
                                if($excellent > 0) {
                                    echo '<i class="bi bi-arrow-up text-success"></i> +' . $excellent;
                                } else {
                                    echo '<i class="bi bi-dash text-muted"></i> Stable';
                                }
                            @endphp
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Performance Table -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Employee Performance Rankings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 40px;">Rank</th>
                                        <th>Employee</th>
                                        <th style="width: 120px;">Position</th>
                                        <th style="width: 150px;">Composite Score</th>
                                        <th style="width: 140px;">Performance Level</th>
                                        <th style="width: 200px;">Score Distribution</th>
                                        <th style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deptKPIs as $index => $kpi)
                                    <tr>
                                        <td>
                                            @if($index === 0)
                                                <span class="badge bg-warning"><i class="bi bi-trophy"></i></span>
                                            @else
                                                <strong>{{ $index + 1 }}</strong>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $kpi['employee']->fullname }}</strong>
                                            <br><small class="text-muted">{{ $kpi['employee']->employee_id ?? 'N/A' }}</small>
                                        </td>
                                        <td>{{ $kpi['employee']->role?->title ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge badge-{{ 
                                                    $kpi['composite_score'] >= 90 ? 'success' : 
                                                    ($kpi['composite_score'] >= 75 ? 'info' : 
                                                    ($kpi['composite_score'] >= 60 ? 'warning' : 'danger'))
                                                }}">
                                                    {{ round($kpi['composite_score'], 2) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            @switch($kpi['performance_level'])
                                                @case('excellent')
                                                    <span class="badge bg-success">Excellent</span>
                                                    @break
                                                @case('good')
                                                    <span class="badge bg-info">Good</span>
                                                    @break
                                                @case('satisfactory')
                                                    <span class="badge bg-warning">Satisfactory</span>
                                                    @break
                                                @case('needs_improvement')
                                                    <span class="badge bg-warning">Needs Improvement</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-danger">Unsatisfactory</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ 
                                                    $kpi['composite_score'] >= 90 ? 'bg-success' : 
                                                    ($kpi['composite_score'] >= 75 ? 'bg-info' : 
                                                    ($kpi['composite_score'] >= 60 ? 'bg-warning' : 'bg-danger'))
                                                }}" style="width: {{ min($kpi['composite_score'], 100) }}%;">
                                                    {{ round($kpi['composite_score'], 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('kpi.show', $kpi['employee']->id) }}?period={{ $period }}" 
                                               class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-2"></i><br>No employees in this department
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

        <!-- Performance Distribution & Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Performance Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $excellent = collect($deptKPIs)->where('performance_level', 'excellent')->count();
                            $good = collect($deptKPIs)->where('performance_level', 'good')->count();
                            $satisfactory = collect($deptKPIs)->where('performance_level', 'satisfactory')->count();
                            $needsImprovement = collect($deptKPIs)->where('performance_level', 'needs_improvement')->count();
                            $unsatisfactory = collect($deptKPIs)->where('performance_level', 'unsatisfactory')->count();
                            $total = count($deptKPIs);
                        @endphp
                        
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-success me-2">{{ $excellent }}</span>
                                        <span>Excellent (90-100)</span>
                                    </div>
                                    <span class="text-muted">{{ round(($excellent/$total)*100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success" style="width: {{ ($excellent/$total)*100 }}%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-info me-2">{{ $good }}</span>
                                        <span>Good (75-89)</span>
                                    </div>
                                    <span class="text-muted">{{ round(($good/$total)*100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ ($good/$total)*100 }}%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-warning me-2">{{ $satisfactory }}</span>
                                        <span>Satisfactory (60-74)</span>
                                    </div>
                                    <span class="text-muted">{{ round(($satisfactory/$total)*100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ ($satisfactory/$total)*100 }}%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-warning me-2">{{ $needsImprovement }}</span>
                                        <span>Needs Improvement (45-59)</span>
                                    </div>
                                    <span class="text-muted">{{ round(($needsImprovement/$total)*100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ ($needsImprovement/$total)*100 }}%;"></div>
                                </div>
                            </div>

                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-danger me-2">{{ $unsatisfactory }}</span>
                                        <span>Unsatisfactory (&lt;45)</span>
                                    </div>
                                    <span class="text-muted">{{ round(($unsatisfactory/$total)*100, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: {{ ($unsatisfactory/$total)*100 }}%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Department Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0">
                                <span class="text-muted">Average Score:</span>
                                <strong class="float-end">{{ round($avgScore, 2) }}/100</strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Highest Score:</span>
                                <strong class="float-end text-success">
                                    {{ round(collect($deptKPIs)->max('composite_score'), 2) }}/100
                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Lowest Score:</span>
                                <strong class="float-end text-danger">
                                    {{ round(collect($deptKPIs)->min('composite_score'), 2) }}/100
                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Standard Deviation:</span>
                                @php
                                    $scores = collect($deptKPIs)->pluck('composite_score');
                                    $mean = $scores->avg();
                                    $variance = $scores->map(fn($x) => pow($x - $mean, 2))->avg();
                                    $stdDev = sqrt($variance);
                                @endphp
                                <strong class="float-end">{{ round($stdDev, 2) }}</strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Total Employees:</span>
                                <strong class="float-end">{{ count($deptEmployees) }}</strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Employees Above Average:</span>
                                <strong class="float-end text-success">
                                    {{ collect($deptKPIs)->where('composite_score', '>', $avgScore)->count() }}
                                </strong>
                            </div>
                            <div class="list-group-item px-0">
                                <span class="text-muted">Employees Below Average:</span>
                                <strong class="float-end text-danger">
                                    {{ collect($deptKPIs)->where('composite_score', '<', $avgScore)->count() }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('reports.export-csv') }}?period={{ $period }}" class="btn btn-outline-success">
                    <i class="bi bi-download"></i> Export Department Data
                </a>
                <a href="{{ route('kpi.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePeriod() {
        const period = document.getElementById('periodSelect').value;
        window.location.href = `{{ route('kpi.department') }}?period=${period}`;
    }
</script>
@endsection
