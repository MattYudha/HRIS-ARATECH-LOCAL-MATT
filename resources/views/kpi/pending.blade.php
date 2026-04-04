@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>Pending KPI Approvals</h3>
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
                    <i class="bi bi-clock"></i>
                    <strong>{{ count($pendingKPIs) }}</strong> KPI menunggu persetujuan Anda untuk periode <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('F Y') }}</strong>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Pending KPIs Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-list-check"></i> Daftar KPI Menunggu Persetujuan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Karyawan</th>
                                        <th>Periode</th>
                                        <th>Composite Score</th>
                                        <th>Performance Level</th>
                                        <th>Submitted At</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingKPIs as $index => $kpi)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $kpi->fullname }}</strong></td>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $kpi->period)->format('M Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $kpi->composite_score >= 90 ? 'success' : 
                                                ($kpi->composite_score >= 75 ? 'info' : 
                                                ($kpi->composite_score >= 60 ? 'warning' : 'danger'))
                                            }}">
                                                {{ round($kpi->composite_score, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($kpi->performance_level)
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
                                        <td>{{ $kpi->submitted_at ? \Carbon\Carbon::parse($kpi->submitted_at)->format('d M Y H:i') : '-' }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('kpi.show', $kpi->employee_id) }}?period={{ $kpi->period }}" 
                                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <form action="{{ route('kpi.approve', $kpi->employee_id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="period" value="{{ $kpi->period }}">
                                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Approve" 
                                                            onclick="return confirm('Setujui KPI {{ $kpi->fullname }}?')">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-outline-danger" title="Reject" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $kpi->employee_id }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $kpi->employee_id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="{{ route('kpi.reject', $kpi->employee_id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="period" value="{{ $kpi->period }}">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Tolak KPI - {{ $kpi->fullname }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Catatan Penolakan</label>
                                                                    <textarea name="notes" class="form-control" rows="3" 
                                                                              placeholder="Berikan alasan penolakan..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-danger">Tolak KPI</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="bi bi-check-circle fs-2 mb-2"></i>
                                            <br>Tidak ada KPI yang menunggu persetujuan.
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

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('kpi.team') }}?period={{ $period }}" class="btn btn-outline-primary">
                    <i class="bi bi-people"></i> Lihat Semua Tim
                </a>
                <a href="{{ route('kpi.department') }}?period={{ $period }}" class="btn btn-outline-info">
                    <i class="bi bi-building"></i> Lihat Departemen
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePeriod() {
        const period = document.getElementById('periodSelect').value;
        window.location.href = `{{ route('kpi.pending') }}?period=${period}`;
    }
</script>
@endsection
