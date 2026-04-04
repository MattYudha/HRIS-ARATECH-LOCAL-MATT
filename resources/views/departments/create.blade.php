@extends('layouts.dashboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('mazer/assets/extensions/choices.js/public/assets/styles/choices.css') }}">
@endpush



<div class="page-heading">

    <!-- PAGE TITLE -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3>New Department</h3>
                <p class="text-subtitle text-muted">Create a new department</p>
            </div>
            <div class="col-md-6 text-md-end">
                <nav aria-label="breadcrumb" class="breadcrumb-header">
                    <ol class="breadcrumb justify-content-md-end">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('departments.index') }}">Departments</a>
                        </li>
                        <li class="breadcrumb-item active">New</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="section">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- ALERT -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('departments.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                Department Name <span class="text-danger">*</span>
                            </label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select
                                name="status"
                                id="status"
                                class="form-select"
                                required
                            >
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        {{-- Manager / Unit Head --}}
                        <div class="col-md-6 mb-3">
                            <label for="manager_id" class="form-label">Manager / Unit Head</label>
                            <select name="manager_id" id="manager_id" class="form-select">
                                <option value="">-- Select Manager / Unit Head --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('manager_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Parent Department --}}
                        <div class="col-md-6 mb-3">
                            <label for="parent_id" class="form-label">Parent Department</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">-- Select Parent --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('parent_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                name="description"
                                id="description"
                                class="form-control"
                                rows="3"
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Department
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

@push('scripts')
<script src="{{ asset('mazer/assets/extensions/choices.js/public/assets/scripts/choices.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Choices for Manager / Unit Head and Parent Department
        const managerChoices = new Choices('#manager_id', {
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: '-- Select Manager / Unit Head --'
        });

        const parentChoices = new Choices('#parent_id', {
            searchEnabled: true,
            itemSelectText: '',
            placeholder: true,
            placeholderValue: '-- Select Parent --'
        });
    });
</script>
@endpush
