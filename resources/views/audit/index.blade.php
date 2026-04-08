@extends('layouts.dashboard')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Audit Trail</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Audit Trail</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-history me-1"></i>
            System Activity Log
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-nowrap" style="min-width: 800px;" id="auditTable">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Resource</th>
                            <th>Details</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->user->name ?? 'System' }}</td>
                            <td>
                                <span class="badge @if($log->event == 'created') bg-success @elseif($log->event == 'updated') bg-warning @else bg-danger @endif">
                                    {{ strtoupper($log->event) }}
                                </span>
                            </td>
                            <td>{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info view-details" 
                                        data-old='@json($log->old_values)' 
                                        data-new='@json($log->new_values)'>
                                    View Diff
                                </button>
                            </td>
                            <td>{{ $log->ip_address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal for Details -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Old Values</h6>
                        <pre id="oldValues" class="bg-light p-3 border rounded"></pre>
                    </div>
                    <div class="col-md-6">
                        <h6>New Values</h6>
                        <pre id="newValues" class="bg-light p-3 border rounded"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const oldPre = document.getElementById('oldValues');
    const newPre = document.getElementById('newValues');

    document.querySelectorAll('.view-details').forEach(btn => {
        btn.addEventListener('click', function() {
            const oldVals = JSON.parse(this.dataset.old || '{}');
            const newVals = JSON.parse(this.dataset.new || '{}');
            
            oldPre.textContent = JSON.stringify(oldVals, null, 2);
            newPre.textContent = JSON.stringify(newVals, null, 2);
            
            modal.show();
        });
    });
});
</script>
@endpush
@endsection
