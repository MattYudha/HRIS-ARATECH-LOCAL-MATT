@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory-dispatches.index') }}">Dispatches</a></li>
            <li class="breadcrumb-item active" aria-current="page">Release Item</li>
        </ol>
    </nav>
    <h3>Release Inventory Item</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inventory-dispatches.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="inventory_id" class="form-label">Inventory Item <span class="text-danger">*</span></label>
                        <select name="inventory_id" id="inventory_id" class="form-select" required>
                            <option value="">-- Select Item --</option>
                            @foreach($inventories as $inv)
                                <option value="{{ $inv->id }}" {{ old('inventory_id') == $inv->id ? 'selected' : '' }}>
                                    {{ $inv->name }} (Available: {{ $inv->quantity }} {{ $inv->item_type == 'habis_pakai' ? '[Consumable]' : '[Asset]' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="employee_id" class="form-label">Released To (Employee) <span class="text-danger">*</span></label>
                        <select name="employee_id" id="employee_id" class="form-select" required>
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->fullname }} ({{ $emp->department->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="{{ old('quantity', 1) }}" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="area" class="form-label">Destination Area</label>
                        <input type="text" name="area" id="area" class="form-control" value="{{ old('area') }}" placeholder="e.g. Lobby, Server Room">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="room" class="form-label">Destination Room</label>
                        <input type="text" name="room" id="room" class="form-control" value="{{ old('room') }}" placeholder="e.g. R-101">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="dispatch_date" class="form-label">Release Date <span class="text-danger">*</span></label>
                        <input type="date" name="dispatch_date" id="dispatch_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">Notes / Purpose</label>
                        <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="alert alert-info py-2">
                    <i class="bi bi-info-circle me-2"></i> A unique barcode will be automatically generated for this dispatch upon saving.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save & Generate Barcode</button>
                    <a href="{{ route('inventory-dispatches.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
