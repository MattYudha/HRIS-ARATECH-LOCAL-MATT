@extends('layouts.dashboard')

@section('content')




<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Employee Update Approvals</h3>
                <p class="text-subtitle text-muted">Review and process profile change requests from employees.</p>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="approvals-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Employee</th>
                            <th>Requested By</th>
                            <th>Changes Count</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#approvals-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('employee-approvals.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'employee.fullname', name: 'employee.fullname'},
            {data: 'requester.name', name: 'requester.name'},
            {
                data: 'new_data', 
                name: 'new_data',
                render: function(data) {
                    return Object.keys(data).length + ' fields';
                }
            },
            {data: 'status_badge', name: 'status_badge'},
            {
                data: 'created_at', 
                name: 'created_at',
                render: function(data) {
                    if(!data) return '-';
                    var d = new Date(data);
                    return d.toLocaleDateString('id-ID') + ' ' + d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush
@endsection
