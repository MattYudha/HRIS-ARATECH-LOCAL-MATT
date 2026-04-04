@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Record Incident/Award</h3>
                <p class="text-subtitle text-muted">Register a new incident or achievement for an employee</p>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('incidents.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Back to List</a>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('incidents.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Employee</label>
                            <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->fullname }} ({{ $emp->nik }})
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Incident Type</label>
                            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror" placeholder="e.g. SP1, Award, Achievement" value="{{ old('type') }}" required>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', date('Y-m-d')) }}" required>
                            @error('incident_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Severity</label>
                            <select name="severity" class="form-select @error('severity') is-invalid @enderror" required>
                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('severity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="investigating" {{ old('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Action Taken</label>
                            <textarea name="action_taken" class="form-control @error('action_taken') is-invalid @enderror" rows="2">{{ old('action_taken') }}</textarea>
                            @error('action_taken') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">Save Incident</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
