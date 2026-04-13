@extends('layouts.dashboard')

@section('content')

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Request Detail</h3>
                <p class="text-subtitle text-muted">Detail pengajuan inventori.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory-requests.index') }}">Inventory Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    
    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Informasi Pengajuan</h4>
                        <span class="badge {{ 
                            $inventoryRequest->status == 'approved' ? 'bg-info' : 
                            ($inventoryRequest->status == 'completed' ? 'bg-success' : 
                            ($inventoryRequest->status == 'pending' ? 'bg-warning text-dark' : 'bg-danger'))
                        }} fs-6">
                            {{ ucfirst($inventoryRequest->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 200px;">Nama Karyawan</th>
                                <td>: {{ $inventoryRequest->employee->fullname }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Permintaan</th>
                                <td>: 
                                    @if($inventoryRequest->request_type == 'new')
                                        <span class="badge bg-primary">Pengadaan Baru</span>
                                    @elseif($inventoryRequest->request_type == 'repair')
                                        <span class="badge bg-warning text-dark">Perbaikan</span>
                                    @else
                                        <span class="badge bg-info">Penggantian</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>: {{ $inventoryRequest->inventory ? $inventoryRequest->inventory->name : $inventoryRequest->item_name }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>: {{ $inventoryRequest->quantity }}</td>
                            </tr>
                            <tr>
                                <th>Alasan</th>
                                <td>: {{ $inventoryRequest->reason }}</td>
                            </tr>
                            <tr>
                                <th>Diajukan Pada</th>
                                <td>: {{ $inventoryRequest->created_at->format('d F Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($inventoryRequest->status != 'pending')
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Informasi Approval</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 200px;">Diproses Oleh</th>
                                <td>: {{ $inventoryRequest->approvedBy ? $inventoryRequest->approvedBy->fullname : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Waktu Proses</th>
                                <td>: {{ $inventoryRequest->approved_at ? $inventoryRequest->approved_at->format('d F Y H:i') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Catatan/Notes</th>
                                <td>: {{ $inventoryRequest->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Actions</h4>
                    </div>
                    <div class="card-body d-grid gap-2">
                        @php $role = session('role'); @endphp
                        @if($inventoryRequest->status == 'pending' && \App\Constants\Roles::isAdmin($role))
                            <a href="{{ route('inventory-requests.approve', $inventoryRequest->id) }}" class="btn btn-success">
                                <i class="bi bi-check-lg"></i> Approve Request
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg"></i> Reject Request
                            </button>
                        @endif

                        @if(\App\Constants\Roles::isAdmin($role) || $inventoryRequest->employee_id == session('employee_id'))
                            <a href="{{ route('inventory-requests.edit', $inventoryRequest->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit Details
                            </a>
                        @endif

                        <a href="{{ route('inventory-requests.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to List
                        </a>

                        @if(\App\Constants\Roles::isAdmin($role))
                            <form action="{{ route('inventory-requests.destroy', $inventoryRequest->id) }}" method="POST" onsubmit="return confirm('Hapus pengajuan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="bi bi-trash"></i> Delete Request
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('inventory-requests.update', $inventoryRequest->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="rejected">
            <input type="hidden" name="request_type" value="{{ $inventoryRequest->request_type }}">
            <input type="hidden" name="item_name" value="{{ $inventoryRequest->item_name }}">
            <input type="hidden" name="inventory_id" value="{{ $inventoryRequest->inventory_id }}">
            <input type="hidden" name="quantity" value="{{ $inventoryRequest->quantity }}">
            <input type="hidden" name="reason" value="{{ $inventoryRequest->reason }}">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">Alasan Penolakan / Catatan</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Confirm Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
