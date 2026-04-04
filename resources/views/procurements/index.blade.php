@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Procurements</li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between align-items-center">
        <h3>Procurement & Purchase Orders</h3>
        <a href="{{ route('procurements.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> New Purchase Order
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="procurement-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Vendor</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Status</th>
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
        $('#procurement-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('procurements.index') }}",
            columns: [
                { data: 'po_number', name: 'po_number' },
                { data: 'vendor.name', name: 'vendor.name' },
                { data: 'order_date', name: 'order_date' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });
    });
</script>
@endpush
@endsection
