@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventory Dispatches</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h3>Inventory Releases & Barcoding</h3>
        <a href="{{ route('inventory-dispatches.create') }}" class="btn btn-primary">
            <i class="bi bi-box-arrow-right me-1"></i> Release New Item
        </a>
    </div>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="dispatch-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Barcode</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Receiver</th>
                            <th>Location/Room</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    $(function() {
        $('#dispatch-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inventory-dispatches.index') }}",
            columns: [
                { data: 'barcode', name: 'barcode', className: 'fw-bold text-primary font-monospace' },
                { data: 'inventory.name', name: 'inventory.name' },
                { data: 'quantity', name: 'quantity', className: 'text-center' },
                { data: 'employee.fullname', name: 'employee.fullname' },
                { data: 'area_room', name: 'area_room', orderable: false },
                { data: 'dispatch_date', name: 'dispatch_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
@endpush
@endsection
