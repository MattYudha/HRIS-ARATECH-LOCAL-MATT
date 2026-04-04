@extends('layouts.dashboard')

@section('content')



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>New Inventory Request</h3>
                <p class="text-subtitle text-muted">Ajukan permintaan barang baru, perbaikan, atau penggantian.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('inventory-requests.index') }}">Inventory Requests</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New</li>
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

                <form action="{{ route('inventory-requests.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="request_type" class="form-label">Tipe Permintaan</label>
                                <select class="form-select @error('request_type') is-invalid @enderror" name="request_type" id="request_type" required>
                                    <option value="new" {{ old('request_type') == 'new' ? 'selected' : '' }}>Pengadaan Baru</option>
                                    <option value="repair" {{ old('request_type') == 'repair' ? 'selected' : '' }}>Perbaikan (Repair)</option>
                                    <option value="replacement" {{ old('request_type') == 'replacement' ? 'selected' : '' }}>Penggantian (Replacement)</option>
                                </select>
                                @error('request_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Jumlah (Quantity)</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3" id="inventory_select_wrapper">
                        <label for="inventory_id" class="form-label">Pilih Barang (Jika perbaikan/penggantian)</label>
                        <select class="form-select @error('inventory_id') is-invalid @enderror" name="inventory_id" id="inventory_id">
                            <option value="">-- Cari Barang --</option>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}" {{ old('inventory_id') == $inventory->id ? 'selected' : '' }}>
                                    {{ $inventory->name }} ({{ $inventory->location }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Kosongkan jika ingin pengadaan barang baru yang belum ada di daftar.</small>
                        @error('inventory_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="item_name_wrapper">
                        <label for="item_name" class="form-label">Nama Barang (Jika barang baru)</label>
                        <input type="text" class="form-control @error('item_name') is-invalid @enderror" name="item_name" id="item_name" value="{{ old('item_name') }}">
                        @error('item_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reason" class="form-label">Alasan Permintaan</label>
                        <textarea class="form-control @error('reason') is-invalid @enderror" name="reason" id="reason" rows="4" required>{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
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
