@extends('layouts.dashboard')

@section('content')



<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventories.index') }}">Inventories</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Inventory Item</li>
        </ol>
    </nav>

    <h3>Edit Inventory Item</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('inventories.update', $inventory) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Item Name --}}
                                <div class="col-md-4 mb-3">
                                    <label for="name" class="form-label">
                                        Item Name <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        class="form-control"
                                        value="{{ old('name', $inventory->name) }}"
                                        required
                                        placeholder="Enter item name"
                                    >
                                </div>

                                {{-- Category --}}
                                <div class="col-md-4 mb-3">
                                    <label for="inventory_category_id" class="form-label">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="inventory_category_id"
                                        id="inventory_category_id"
                                        class="form-select"
                                        required
                                    >
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                            <option
                                                value="{{ $category->id }}"
                                                {{ old('inventory_category_id', $inventory->inventory_category_id) == $category->id ? 'selected' : '' }}
                                            >
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Item Type --}}
                                <div class="col-md-4 mb-3">
                                    <label for="item_type" class="form-label">
                                        Item Type <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="item_type"
                                        id="item_type"
                                        class="form-select"
                                        required
                                    >
                                        <option value="habis_pakai" {{ old('item_type', $inventory->item_type) == 'habis_pakai' ? 'selected' : '' }}>Habis Pakai (Consumable)</option>
                                        <option value="tidak_habis_pakai" {{ old('item_type', $inventory->item_type) == 'tidak_habis_pakai' ? 'selected' : '' }}>Tidak Habis Pakai (Asset)</option>
                                    </select>
                                </div>

                                {{-- Quantity --}}
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">
                                        Quantity <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        class="form-control"
                                        min="0"
                                        value="{{ old('quantity', $inventory->quantity) }}"
                                        required
                                        placeholder="Enter quantity"
                                    >
                                </div>

                                {{-- Min Stock Threshold --}}
                                <div class="col-md-6 mb-3">
                                    <label for="min_stock_threshold" class="form-label">
                                        Min Stock Threshold
                                    </label>
                                    <input
                                        type="number"
                                        name="min_stock_threshold"
                                        id="min_stock_threshold"
                                        class="form-control"
                                        min="0"
                                        value="{{ old('min_stock_threshold', $inventory->min_stock_threshold) }}"
                                        placeholder="Enter Min Stock Threshold"
                                    >
                                    <small class="text-muted">Set to 0 to disable low stock alerts.</small>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select
                                        name="status"
                                        id="status"
                                        class="form-select"
                                        required
                                    >
                                        <option value="active" {{ old('status', $inventory->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $inventory->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="damaged" {{ old('status', $inventory->status) == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>
                                </div>

                                {{-- Location --}}
                                <div class="col-md-4 mb-3">
                                    <label for="location" class="form-label">Main Location</label>
                                    <input
                                        type="text"
                                        name="location"
                                        id="location"
                                        class="form-control"
                                        value="{{ old('location', $inventory->location) }}"
                                        placeholder="Enter location"
                                    >
                                </div>

                                {{-- Area --}}
                                <div class="col-md-4 mb-3">
                                    <label for="area" class="form-label">Area</label>
                                    <input
                                        type="text"
                                        name="area"
                                        id="area"
                                        class="form-control"
                                        value="{{ old('area', $inventory->area) }}"
                                        placeholder="Enter area"
                                    >
                                </div>

                                {{-- Room --}}
                                <div class="col-md-4 mb-3">
                                    <label for="room" class="form-label">Room</label>
                                    <input
                                        type="text"
                                        name="room"
                                        id="room"
                                        class="form-control"
                                        value="{{ old('room', $inventory->room) }}"
                                        placeholder="Enter room"
                                    >
                                </div>

                                {{-- Purchase Date --}}
                                <div class="col-md-6 mb-3">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input
                                        type="date"
                                        name="purchase_date"
                                        id="purchase_date"
                                        class="form-control"
                                        value="{{ old('purchase_date', optional($inventory->purchase_date)->format('Y-m-d')) }}"
                                    >
                                </div>

                                {{-- Description --}}
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        class="form-control"
                                        rows="4"
                                        placeholder="Enter description"
                                    >{{ old('description', $inventory->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>
                                <a href="{{ route('inventories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
