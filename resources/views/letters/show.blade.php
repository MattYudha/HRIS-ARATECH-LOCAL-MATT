@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <h3>Letter Details</h3>
</div>


<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            @if($letter->status === 'draft' && Auth::user()->id === $letter->user_id)
                <a href="{{ route('letters.edit', $letter) }}" class="btn btn-warning">Edit</a>
                <form method="POST" action="{{ route('letters.destroy', $letter) }}" style="display:inline;" onsubmit="return confirm('Delete this letter?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <form method="POST" action="{{ route('letters.submit', $letter) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Submit for Approval</button>
                </form>
            @endif
            
            @if($letter->status === 'pending' && Auth::user()->id === $letter->user_id)
                <a href="{{ route('letters.edit', $letter) }}" class="btn btn-warning">Update</a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Letter Number:</strong> {{ $letter->letter_number ?? 'Draft' }}</p>
                    <p><strong>Status:</strong> <span class="badge bg-{{ $letter->status === 'draft' ? 'secondary' : ($letter->status === 'pending' ? 'warning' : ($letter->status === 'approved' ? 'success' : 'info')) }}">{{ ucfirst($letter->status) }}</span></p>
                    <p><strong>From:</strong> {{ $letter->user->name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Type:</strong> {{ ucfirst($letter->letter_type) }}</p>
                    <p><strong>Created:</strong> {{ $letter->created_date->format('d M Y H:i') }}</p>
                    @if($letter->approver)
                        <p><strong>Approved by:</strong> {{ $letter->approver->name }}</p>
                    @endif
                    @if($letter->purpose)
                        <p><strong>Purpose:</strong> {{ $letter->purpose }}</p>
                    @endif
                    @if($letter->end_date)
                        <p><strong>End Date:</strong> {{ $letter->end_date }}</p>
                    @endif
                    @if($letter->days)
                        <p><strong>Days:</strong> {{ $letter->days }}</p>
                    @endif
                    @if($letter->period)
                        <p><strong>Period:</strong> {{ $letter->period }}</p>
                    @endif
                    @if($letter->recommender_name)
                        <p><strong>Recommender:</strong> {{ $letter->recommender_name }}</p>
                    @endif
                    @if($letter->reason)
                        <p><strong>Reason:</strong> {{ $letter->reason }}</p>
                    @endif
                </div>
            </div>

            <hr>

            <h5>{{ $letter->subject }}</h5>
            <div class="mt-3" style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                {!! nl2br($letter->formatted_content) !!}
            </div>

            @if($letter->status === 'pending')
                @php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canApprove = in_array($userRole, ['HR Administrator', 'Super Admin']);
                @endphp
                @if($canApprove)
                    <hr>
                    <div class="mt-4">
                        <form method="POST" action="{{ route('letters.approve', $letter) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                    </div>
                @else
                    <hr>
                    <div class="alert alert-info mt-3">
                        <strong>Pending Approval:</strong> This letter is awaiting approval from HR Administrator.
                    </div>
                @endif
            @endif

            @if($letter->status === 'approved')
                @php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canPrint = in_array($userRole, ['HR Administrator', 'Super Admin']);
                @endphp
                @if($canPrint)
                    <hr>
                    <div class="mt-4">
                        <a href="{{ route('letters.export', $letter) }}" class="btn btn-outline-info">Download Draft PDF</a>
                        <form method="POST" action="{{ route('letters.print', $letter) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary">Mark as Printed</button>
                        </form>
                    </div>
                @else
                    <hr>
                    <div class="alert alert-success mt-3">
                        <strong>Approved:</strong> This letter has been approved by HR Administrator.
                    </div>
                @endif
            @endif
            
            @if($letter->status === 'printed')
                @php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canPrint = in_array($userRole, ['HR Administrator', 'Super Admin']);
                @endphp
                @if($canPrint)
                    <hr>
                    <div class="mt-4">
                        <a href="{{ route('letters.export', $letter) }}" class="btn btn-outline-info">Download Draft PDF</a>
                    </div>
                @else
                    <hr>
                    <div class="alert alert-success mt-3">
                        <strong>Completed:</strong> This letter has been printed.
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Digital Signatures Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Digital Signatures</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    @php
                        $signatures = $letter->signatures()->count();
                    @endphp
                    <p><strong>Total Signatures:</strong> {{ $signatures }}</p>
                    
                    @if($letter->status !== 'draft')
                        @php
                            $hasSigned = $letter->signatures()->where('user_id', Auth::id())->exists();
                        @endphp
                        @if(!$hasSigned)
                            <a href="{{ route('signatures.pad', ['signable' => 'letter', 'id' => $letter->id]) }}" class="btn btn-primary">+ Sign Document</a>
                        @endif
                        <a href="{{ route('signatures.list', ['signable' => 'letter', 'id' => $letter->id]) }}" class="btn btn-info">View All Signatures</a>
                        
                        @if($letter->signatures()->exists())
                            <a href="{{ route('signatures.download', $letter->signatures()->first()) }}" class="btn btn-success">
                                <i class="bi bi-file-earmark-check"></i> Download Signed PDF (Final)
                            </a>
                        @endif
                    @else
                        <div class="alert alert-warning mt-2 mb-0">
                            <small>You can sign this document after it has been submitted for approval.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Reject Modal -->
@php
    $userRole = Auth::user()->employee->role->title ?? null;
    $canReject = in_array($userRole, ['HR Administrator', 'Super Admin']);
@endphp
@if($canReject)
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('letters.reject', $letter) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Letter</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
