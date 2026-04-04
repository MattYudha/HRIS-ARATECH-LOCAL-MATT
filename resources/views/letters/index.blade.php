@extends('layouts.dashboard')

@section('content')

@php
    $userRole = Auth::user()->employee?->role?->title;
    $isHROrPowerUser = in_array($userRole, ['HR Administrator', 'Super Admin']);
    $pendingCount = $isHROrPowerUser ? \App\Models\Letter::where('status', 'pending')->count() : 0;
@endphp
<div class="page-heading">
    <h3>Letters
        @if($isHROrPowerUser && $pendingCount > 0)
            <span class="badge bg-warning">{{ $pendingCount }} Pending</span>
        @endif
    </h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('letters.create') }}" class="btn btn-primary">+ Create Letter</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="letter-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Letter Number</th>
                        <th>Subject</th>
                        <th>From</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    $(function() {
        $('#letter-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('letters.index') }}",
            order: [[4, 'desc']],
            columns: [
                { data: 'letter_number', name: 'letter_number', defaultContent: '<em>Draft</em>' },
                { data: 'subject', name: 'subject' },
                { data: 'user.name', name: 'user.name' },
                { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                { data: 'created_date', name: 'created_date' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ]
        });

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus surat ini?');
        });
    });
</script>
@endpush
@endsection
