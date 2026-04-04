@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>Department Details</h3>
                <p class="text-subtitle text-muted">Detailed information for {{ $department->name }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('departments.index') }}">Departments</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $department->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Department Info -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">General Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Department Name</label>
                            <p class="text-muted">{{ $department->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Status</label>
                            <div>
                                <span class="badge {{ $department->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($department->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Manager / Unit Head</label>
                            <p class="text-muted">
                                @if($department->manager)
                                    <a href="{{ route('employees.show', $department->manager->id) }}">
                                        {{ $department->manager->fullname }}
                                    </a>
                                @else
                                    <span class="text-muted italic">No manager assigned</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Parent Department</label>
                            <p class="text-muted">
                                @if($department->parent)
                                    <a href="{{ route('departments.show', $department->parent->id) }}">
                                        {{ $department->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted italic">None</span>
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Description</label>
                            <p class="text-muted">{{ $department->description ?: 'No description provided.' }}</p>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('departments.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Hierarchy & Employees -->
            <div class="col-md-8">
                <!-- Sub-departments -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h4 class="card-title">Sub-Departments</h4>
                    </div>
                    <div class="card-body">
                        @if($department->children->count() > 0)
                            <div class="list-group">
                                @foreach($department->children as $child)
                                    <a href="{{ route('departments.show', $child->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        {{ $child->name }}
                                        <span class="badge bg-primary rounded-pill">{{ $child->employees->count() }} Employees</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted italic">No sub-departments found.</p>
                        @endif
                    </div>
                </div>

                <!-- Employees in this Department -->
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Employees ({{ $department->employees->count() }})</h4>
                    </div>
                    <div class="card-body">
                        @if($department->employees->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Position</th>
                                            <th>Role</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($department->employees as $emp)
                                            <tr>
                                                <td>{{ $emp->fullname }}</td>
                                                <td>{{ $emp->position->name ?? '-' }}</td>
                                                <td>{{ $emp->role->title ?? '-' }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('employees.show', $emp->id) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted italic">No employees assigned to this department.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
