@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Leave Requests</h3>
                <p class="text-subtitle text-muted">Manage leave data.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">Leave Requests</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                <div class="d-flex mb-3">
                    <a href="{{ route('leave-requests.create') }}" class="btn btn-primary ms-auto">
                        New Leave Request
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped align-middle w-100" id="leave-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#leave-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('leave-requests.index') }}",
                type: "GET",
            },
            order: [[2, 'desc']],
            columns: [
                {
                    data: 'employee.fullname',
                    name: 'employee.fullname',
                    defaultContent: '<em>Unknown</em>'
                },
                { data: 'leave_type', name: 'leave_type' },
                { data: 'start_date', name: 'start_date' },
                { data: 'end_date', name: 'end_date' },
                {
                    data: 'status_badge',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus pengajuan cuti ini?');
        });
    });
</script>
@endpush
