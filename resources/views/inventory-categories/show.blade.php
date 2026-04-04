@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory-categories.index') }}">Inventory Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>
    <h3>Category: {{ $category->name }}</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row">
            <!-- Category Info -->
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Category Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="font-bold">Name</label>
                            <p class="text-muted">{{ $category->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Description</label>
                            <p class="text-muted">{{ $category->description ?: 'No description provided.' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="font-bold">Total Items</label>
                            <p class="text-muted">{{ $category->inventories->count() }} items</p>
                        </div>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="{{ route('inventory-categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="{{ route('inventory-categories.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items in this Category -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Items in {{ $category->name }}</h4>
                    </div>
                    <div class="card-body">
                        @if($category->inventories->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="items-table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>SKU</th>
                                            <th class="text-center">Stock</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->inventories as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->sku }}</td>
                                                <td class="text-center">
                                                    <span class="badge {{ $item->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $item->stock }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('inventories.show', $item->id) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted italic">No items found in this category.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        $('#items-table').DataTable();
    });
</script>
@endpush
