@extends('layouts.dashboard')

@section('content')



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventory Usage Logs</li>
        </ol>
    </nav>

    <h3>Inventory Usage Logs</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-body">

                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Usage Logs</h4>
                    <a href="{{ route('inventory-usage-logs.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Log Usage
                    </a>
                </div>

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="usage-log-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Employee</th>
                                <th>Borrowed Date</th>
                                <th>Returned Date</th>
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
</div>

@endsection

@push('scripts')
<script>
    $(function() {
        $('#usage-log-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('inventory-usage-logs.index') }}",
            columns: [
                { data: 'inventory.name', name: 'inventory.name' },
                { data: 'employee.fullname', name: 'employee.fullname' },
                { data: 'borrowed_date', name: 'borrowed_date' },
                { data: 'returned_date', name: 'returned_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', 'form[action*="destroy"]', function(e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus log penggunaan ini?');
        });
    });
</script>
@endpush
