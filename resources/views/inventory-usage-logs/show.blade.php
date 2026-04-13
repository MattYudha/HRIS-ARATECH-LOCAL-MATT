@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Inventory Usage Detail</h3>
        <a href="{{ route('inventory-usage-logs.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Employee:</strong>
                    <p class="text-muted">{{ $log->employee->fullname }} ({{ $log->employee->department->name ?? '-' }})</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Inventory Item:</strong>
                    <p class="text-muted">{{ $log->inventory->item_name }} ({{ $log->inventory->item_code }})</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Borrowed Date:</strong>
                    <p class="text-muted">{{ $log->borrowed_date ? \Carbon\Carbon::parse($log->borrowed_date)->format('d M Y H:i') : '-' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Returned Date:</strong>
                    <p class="text-muted">
                        @if($log->returned_date)
                            {{ \Carbon\Carbon::parse($log->returned_date)->format('d M Y H:i') }}
                        @else
                            <span class="badge bg-warning">Not Returned</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-12 mb-3">
                    <strong>Notes:</strong>
                    <div class="p-3 bg-light rounded">
                        {{ $log->notes ?: 'No notes provided.' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            @if(\App\Constants\Roles::isAdmin(session('role')))
            <a href="{{ route('inventory-usage-logs.edit', $log->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <form action="{{ route('inventory-usage-logs.destroy', $log->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this log?')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
