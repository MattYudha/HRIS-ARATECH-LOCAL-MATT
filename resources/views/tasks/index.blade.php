@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Tasks</h3>
                <p class="text-subtitle text-muted">Manage tasks data</p>
            </div>
            <div class="col-12 col-md-6">
                <nav class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tasks</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

                {{-- Header Action --}}
                <div class="d-flex justify-content-end mb-3">
                    @php
                        $userRole = session('role');
                        $canManageTasks = \App\Constants\Roles::isAdmin($userRole) || $userRole === \App\Constants\Roles::MANAGER_UNIT_HEAD;
                    @endphp

                    @if ($canManageTasks)
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> New Task
                        </a>
                    @endif
                </div>

                {{-- Alert --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="task-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Assigned To</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
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
        $('#task-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('tasks.index') }}",
            columns: [
                { data: 'title', name: 'title' },
                { data: 'employee.fullname', name: 'employee.fullname', defaultContent: '<em>Unknown</em>' },
                { data: 'due_date', name: 'due_date' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false, className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus tugas ini?');
        });
    });
</script>
@endpush
