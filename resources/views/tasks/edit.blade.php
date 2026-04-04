@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Edit Task</h3>
                <p class="text-subtitle text-muted">Update task information</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('tasks.index') }}">Tasks</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @elseif(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- LEFT -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Title</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title"
                                            value="{{ old('title', $task->title) }}"
                                            required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Assigned To</label>
                                        <select class="form-select @error('assigned_to') is-invalid @enderror"
                                            name="assigned_to" required>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    @selected(old('assigned_to', $task->assigned_to) == $employee->id)>
                                                    {{ $employee->fullname }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- RIGHT -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Due Date</label>
                                        <input type="date"
                                            class="form-control @error('due_date') is-invalid @enderror"
                                            name="due_date"
                                            value="{{ old('due_date', $task->due_date) }}"
                                            required>
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="pending" @selected(old('status', $task->status) == 'pending')>Pending</option>
                                            <option value="on progress" @selected(old('status', $task->status) == 'on progress')>On Progress</option>
                                            <option value="done" @selected(old('status', $task->status) == 'done')>Done</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-4">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    name="description" rows="4">{{ old('description', $task->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            <!-- ACTION -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Task
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection
