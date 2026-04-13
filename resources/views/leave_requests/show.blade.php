@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Leave Request Details</h3>
                <p class="text-subtitle text-muted">Detailed information for leave request #{{ $leaveRequest->id }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('leave-requests.index') }}">Leave Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Request Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Employee Name</label>
                            <p class="text-muted">
                                <a href="{{ route('employees.show', $leaveRequest->employee->id) }}">
                                    {{ $leaveRequest->employee->fullname }}
                                </a>
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Leave Type</label>
                            <p class="text-muted">{{ ucfirst($leaveRequest->leave_type) }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="font-bold">Start Date</label>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="font-bold">End Date</label>
                                    <p class="text-muted">{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Duration</label>
                            <p class="text-muted">
                                {{ \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1 }} Days
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Status</label>
                            <div>
                                @php
                                    $statusClass = match($leaveRequest->status) {
                                        'confirmed' => 'bg-success',
                                        'pending' => 'bg-warning text-dark',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ ucfirst($leaveRequest->status) }}
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            @if($leaveRequest->status == 'pending' && (\App\Constants\Roles::isAdmin(session('role')) || session('role') === \App\Constants\Roles::MANAGER_UNIT_HEAD))
                                <a href="{{ url('leave-requests/confirm/'.$leaveRequest->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-check-lg"></i> Confirm
                                </a>
                                <a href="{{ url('leave-requests/reject/'.$leaveRequest->id) }}" class="btn btn-danger btn-sm">
                                    <i class="bi bi-x-lg"></i> Reject
                                </a>
                            @endif
                            <a href="{{ route('leave-requests.edit', $leaveRequest->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <!-- Employee Summary context -->
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Employee Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Department</label>
                            <p class="text-muted">{{ $leaveRequest->employee->department->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Current Leave Balance</label>
                            @php
                                $balance = $leaveRequest->employee->getLeaveBalance($leaveRequest->leave_type);
                            @endphp
                            <p class="text-muted">
                                <strong>{{ $balance->balance }}</strong> days remaining (Total: {{ $balance->total }}, Taken: {{ $balance->taken }})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
