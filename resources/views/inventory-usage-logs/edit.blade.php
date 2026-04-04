@extends('layouts.dashboard')

@section('content')



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory-usage-logs.index') }}">Inventory Usage Logs</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Usage Log</li>
        </ol>
    </nav>

    <h3>Edit Usage Log</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('inventory-usage-logs.update', $log) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                {{-- Item --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Item <span class="text-danger">*</span>
                                    </label>
                                    <select name="inventory_id" class="form-select" required>
                                        <option value="">Select Item</option>
                                        @foreach($inventories as $inventory)
                                            <option value="{{ $inventory->id }}"
                                                {{ old('inventory_id', $log->inventory_id) == $inventory->id ? 'selected' : '' }}>
                                                {{ $inventory->name }} ({{ $inventory->category->name ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Employee --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Employee <span class="text-danger">*</span>
                                    </label>
                                    <select name="employee_id" class="form-select" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id', $log->employee_id) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->fullname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Borrowed Date --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Borrowed Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local"
                                           name="borrowed_date"
                                           class="form-control"
                                           value="{{ old('borrowed_date', $log->borrowed_date->format('Y-m-d\TH:i')) }}"
                                           required>
                                </div>

                                {{-- Returned Date --}}
                                <div class="col-md-6">
                                    <label class="form-label">Returned Date</label>
                                    <input type="datetime-local"
                                           name="returned_date"
                                           class="form-control"
                                           value="{{ old(
                                               'returned_date',
                                               $log->returned_date ? $log->returned_date->format('Y-m-d\TH:i') : ''
                                           ) }}">
                                </div>

                                {{-- Notes --}}
                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes"
                                              rows="4"
                                              class="form-control"
                                              placeholder="Enter notes">{{ old('notes', $log->notes) }}</textarea>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-pencil-square me-1"></i> Update
                                </button>

                                <a href="{{ route('inventory-usage-logs.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
