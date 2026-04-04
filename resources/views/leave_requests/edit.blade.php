@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Requests</h3>
                <p class="text-subtitle text-muted">Manage leave data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
               <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
  					  <ol class="breadcrumb">
        				<li class="breadcrumb-item">
            			<a href="{{ route('dashboard') }}">Dashboard</a>
                      </li>
                      <li class="breadcrumb-item">
                          <a href="{{ route('leave-requests.index') }}">Leave Requests</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">
                          Edit
                      </li>
                  </ol>
              </nav>

            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="card">
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">

                <form action="{{ route('leave-requests.update', $leaveRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Employee</label>
                        @if(in_array(session('role'), ['HR Administrator', 'Super Admin', 'Manager / Unit Head']))
                            <select class="form-select @error('employee_id') is-invalid @enderror" name="employee_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" 
                                        @if(old('employee_id', $leaveRequest->employee_id) == $employee->id) selected @endif>
                                        {{ $employee->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <div class="form-control-plaintext fw-bold">{{ $leaveRequest->employee->fullname }}</div>
                            <input type="hidden" name="employee_id" value="{{ $leaveRequest->employee_id }}">
                        @endif
                        @error('employee_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="leave_type" class="form-label">Leave Type</label>
                        <input type="text" class="form-control @error('leave_type') is-invalid @enderror" name="leave_type" value="{{ old('leave_type', $leaveRequest->leave_type) }}" required>
                        @error('leave_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror datetime" name="start_date" value="{{ old('start_date', $leaveRequest->start_date) }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror datetime" name="end_date" value="{{ old('end_date', $leaveRequest->end_date) }}" required>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        @if(in_array(session('role'), ['HR Administrator', 'Super Admin', 'Manager / Unit Head']))
                            <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                <option value="">Select Status</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" 
                                        @if(old('status', $leaveRequest->status) == $status) selected @endif>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            @php
                                $class = match($leaveRequest->status) {
                                    'confirmed' => 'bg-success',
                                    'pending' => 'bg-warning text-dark',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <div>
                                <span class="badge {{ $class }}">{{ ucfirst($leaveRequest->status) }}</span>
                            </div>
                            <input type="hidden" name="status" value="{{ $leaveRequest->status }}">
                        @endif
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Leave Request</button>
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">Back to Leave Requests</a>
                </form>
 


            </div>
        </div>
    </section>
</div>

@endsection