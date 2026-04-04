@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Vendors</li>
        </ol>
    </nav>
    <h3>Vendor Management</h3>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">Vendor List</h4>
                <a href="{{ route('vendors.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Add Vendor
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle" id="vendor-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Contact Person</th>
                            <th>Email</th>
                            <th>Phone</th>
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
        $('#vendor-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('vendors.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        $(document).on('submit', 'form[action*="destroy"]', function(e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus vendor ini?');
        });
    });
</script>
@endpush
@endsection
