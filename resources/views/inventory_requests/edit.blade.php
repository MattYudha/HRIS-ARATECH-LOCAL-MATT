@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Inventory Request</h3>
                <p class="text-subtitle text-muted">Perbarui data pengajuan.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory-requests.index') }}">Inventory Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
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

                <form action="{{ route('inventory-requests.update', $inventoryRequest->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    @php $isAdmin = \App\Constants\Roles::isAdmin(session('role')); @endphp

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="request_type" class="form-label">Tipe Permintaan</label>
                                <select class="form-select @error('request_type') is-invalid @enderror" name="request_type" id="request_type" required {{ !$isAdmin && $inventoryRequest->status != 'pending' ? 'disabled' : '' }}>
                                    <option value="new" {{ old('request_type', $inventoryRequest->request_type) == 'new' ? 'selected' : '' }}>Pengadaan Baru</option>
                                    <option value="repair" {{ old('request_type', $inventoryRequest->request_type) == 'repair' ? 'selected' : '' }}>Perbaikan (Repair)</option>
                                    <option value="replacement" {{ old('request_type', $inventoryRequest->request_type) == 'replacement' ? 'selected' : '' }}>Penggantian (Replacement)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah (Quantity)</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" value="{{ old('quantity', $inventoryRequest->quantity) }}" min="1" required {{ !$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="inventory_select_wrapper">
                        <label for="inventory_id" class="form-label">Pilih Barang (Jika perbaikan/penggantian)</label>
                        <select class="form-select @error('inventory_id') is-invalid @enderror" name="inventory_id" id="inventory_id" {{ !$isAdmin && $inventoryRequest->status != 'pending' ? 'disabled' : '' }}>
                            <option value="">-- Cari Barang --</option>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}" {{ old('inventory_id', $inventoryRequest->inventory_id) == $inventory->id ? 'selected' : '' }}>
                                    {{ $inventory->name }} ({{ $inventory->location }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="item_name_wrapper">
                        <label for="item_name" class="form-label">Nama Barang (Jika barang baru)</label>
                        <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name" id="item_name" value="{{ old('item_name', $inventoryRequest->item_name) }}" {{ !$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Permintaan</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" rows="4" required {{ !$isAdmin && $inventoryRequest->status != 'pending' ? 'readonly' : '' }}>{{ old('reason', $inventoryRequest->reason) }}</textarea>
                    </div>

                    @if($isAdmin)
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                                    <option value="pending" {{ old('status', $inventoryRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('status', $inventoryRequest->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('status', $inventoryRequest->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="completed" {{ old('status', $inventoryRequest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan Admin</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes" rows="3">{{ old('notes', $inventoryRequest->notes) }}</textarea>
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Request</button>
                        <a href="{{ route('inventory-requests.index') }}" class="btn btn-outline-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleFields() {
            var type = $('#request_type').val();
            if (type === 'new') {
                $('#inventory_select_wrapper').hide();
                $('#item_name_wrapper').show();
            } else {
                $('#inventory_select_wrapper').show();
                $('#item_name_wrapper').hide();
            }
        }

        $('#request_type').change(toggleFields);
        toggleFields();
    });
</script>
@endpush
@endsection
