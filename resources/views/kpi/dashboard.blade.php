@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>My KPI Dashboard</h3>
</div>

<div class="page-content">
    <div class="container-fluid">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <h6 class="text-primary font-weight-bold mb-1">Composite Score</h6>
                        <h2 class="mb-0">{{ round($compositeScore, 2) }}/100</h2>
                        <small class="text-muted">Overall Performance</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <h6 class="text-success font-weight-bold mb-1">Performance Level</h6>
                        <h4 class="mb-0">
                            @switch($performanceLevel)
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
                        </h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <h6 class="text-info font-weight-bold mb-1">KPIs Achieved</h6>
                        <h2 class="mb-0">{{ $kpiRecords->where('status', 'achieved')->count() }}/{{ $kpiRecords->count() }}</h2>
                        <small class="text-muted">Targets Met</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <h6 class="text-warning font-weight-bold mb-1">Period</h6>
                        <h4 class="mb-0">{{ \Carbon\Carbon::createFromFormat('Y-m', $period)->format('M Y') }}</h4>
                        <small class="text-muted">{{ $period }}</small>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Submission Status -->
        @php
            $firstRecord = $kpiRecords->first();
            $submissionStatus = $firstRecord->submission_status ?? 'draft';
            $reviewerNotes = $firstRecord->reviewer_notes ?? null;
        @endphp
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card {{ $submissionStatus === 'approved' ? 'border-success' : ($submissionStatus === 'rejected' ? 'border-danger' : ($submissionStatus === 'submitted' ? 'border-info' : 'border-secondary')) }}">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Status Pengajuan KPI</h6>
                            @switch($submissionStatus)
                                @case('draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    <small class="text-muted ms-2">Belum disubmit ke atasan</small>
                                    @break
                                @case('submitted')
                                    <span class="badge bg-info">Submitted</span>
                                    <small class="text-muted ms-2">Menunggu persetujuan atasan</small>
                                    @break
                                @case('approved')
                                    <span class="badge bg-success">Approved</span>
                                    <small class="text-muted ms-2">KPI telah disetujui</small>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                    <small class="text-muted ms-2">KPI ditolak - silakan perbaiki</small>
                                    @break
                            @endswitch

                            @if($submissionStatus === 'rejected' && $reviewerNotes)
                            <div class="alert alert-warning mt-2 mb-0">
                                <i class="bi bi-exclamation-triangle"></i>
                                <strong>Catatan dari Atasan:</strong> {{ $reviewerNotes }}
                            </div>
                            @endif
                        </div>
                        <div>
                            @if($submissionStatus === 'draft' || $submissionStatus === 'rejected')
                                @if($employee->supervisor_id)
                                <form action="{{ route('kpi.submit', $employee->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="period" value="{{ $period }}">
                                    <button type="submit" class="btn btn-outline-primary submit-confirm" data-message="Submit KPI untuk review oleh atasan?">
                                        <i class="bi bi-send"></i> Submit untuk Review
                                    </button>
                                </form>
                                @else
                                <span class="text-muted"><i class="bi bi-info-circle"></i> Tidak ada atasan langsung</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Categories -->
        @foreach($kpisByCategory as $category => $records)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $category }} Metrics</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>KPI</th>
                                        <th>Actual Value</th>
                                        <th>Target Value</th>
                                        <th>Achievement %</th>
                                        <th>Status</th>
                                        <th>Notes</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($records as $record)
                                    <tr>
                                        <td>
                                            <strong>{{ $record->kpi->name }}</strong><br>
                                            <small class="text-muted">{{ $record->kpi->unit }}</small>
                                        </td>
                                        <td>{{ $record->actual_value }}</td>
                                        <td>{{ $record->target_value }}</td>
                                        <td>
                                            @php
                                                $achievement = $record->getAchievementPercentage();
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $achievement >= 100 ? 'bg-success' : ($achievement >= 80 ? 'bg-warning' : 'bg-danger') }}" 
                                                     role="progressbar" 
                                                     style="width: {{ min($achievement, 100) }}%" 
                                                     aria-valuenow="{{ $achievement }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ round($achievement, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @switch($record->status)
                                                @case('achieved')
                                                    <span class="badge badge-success">Achieved</span>
                                                    @break
                                                @case('warning')
                                                    <span class="badge badge-warning">Warning</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-danger">Critical</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($record->notes)
                                                <small class="text-muted d-block text-truncate" style="max-width: 150px;" title="{{ $record->notes }}">
                                                    {{ $record->notes }}
                                                </small>
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(in_array($submissionStatus, ['draft', 'rejected']))
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-kpi" 
                                                data-id="{{ $record->id }}"
                                                data-name="{{ $record->kpi->name }}"
                                                data-actual="{{ $record->actual_value }}"
                                                data-notes="{{ $record->notes }}"
                                                data-auto="{{ $record->kpi->metric_category ? 'true' : 'false' }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editKPIModal">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            @else
                                            <span class="text-muted small"><i class="bi bi-lock"></i></span>
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
        @endforeach

        <!-- Incidents -->
        @if($incidents->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Active Incidents</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($incidents as $incident)
                                    <tr>
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
                                        <td><small>{{ $incident->description }}</small></td>
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

        <div class="row mt-4">
            <div class="col-md-12">
                <a href="{{ route('reports.export-pdf', $employee->id) }}?period={{ $period }}" class="btn btn-outline-primary" target="_blank">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF Report
                </a>
                <a href="{{ route('kpi.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit KPI Modal -->
<div class="modal fade" id="editKPIModal" tabindex="-1" aria-labelledby="editKPIModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editKPIForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editKPIModalLabel">Update KPI: <span id="modalKPIName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="actualValueGroup" class="mb-3">
                        <label for="actual_value" class="form-label">Nilai Aktual</label>
                        <input type="number" step="0.01" class="form-control" id="actual_value" name="actual_value">
                        <div id="autoCalculatedHint" class="form-text text-info d-none">
                            <i class="bi bi-info-circle"></i> Nilai ini dihitung otomatis oleh sistem dan tidak dapat diubah manual.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan/Penjelasan</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Tambahkan penjelasan mengenai pencapaian Anda..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(function() {
    // Edit KPI Modal logic
    $('.edit-kpi').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const actual = $(this).data('actual');
        const notes = $(this).data('notes');
        const isAuto = $(this).data('auto');

        $('#modalKPIName').text(name);
        $('#notes').val(notes);
        
        const form = $('#editKPIForm');
        form.attr('action', `/kpi/record/${id}`);

        if (isAuto) {
            $('#actual_value').val(actual).attr('readonly', true).addClass('bg-light');
            $('#autoCalculatedHint').removeClass('d-none');
        } else {
            $('#actual_value').val(actual).attr('readonly', false).removeClass('bg-light');
            $('#autoCalculatedHint').addClass('d-none');
        }
    });

    $('.submit-confirm').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const msg = $(this).data('message') || 'Konfirmasi tindakan ini?';
        
        Swal.fire({
            title: 'Konfirmasi',
            text: msg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection
