@extends('layouts.dashboard')

@section('content')



<div class="page-heading mb-3">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Inventory Categories</li>
        </ol>
    </nav>

    <h3>Inventory Categories</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Inventory Categories</h5>
                    <a href="{{ route('inventory-categories.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="categories-table">
                        <thead>
                            <tr>
                                <th style="width: 25%">Name</th>
                                <th style="width: 35%">Description</th>
                                <th style="width: 15%">Items Count</th>
                                <th style="width: 25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->description }}</td>
                                    <td>{{ $category->inventories->count() }}</td>
                                    <td class="text-center">
                                        <!-- View Icon -->
                                        <a href="{{ route('inventory-categories.show', $category) }}"
                                           class="btn btn-sm btn-outline-info me-1"
                                           title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Edit Icon -->
                                        <a href="{{ route('inventory-categories.edit', $category) }}"
                                           class="btn btn-sm btn-outline-warning me-1"
                                           title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <!-- Delete Icon -->
                                        <form method="POST"
                                              action="{{ route('inventory-categories.destroy', $category) }}"
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No categories found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

@push('scripts')
<script>
    $(function() {
        $('#categories-table').DataTable();

        // delete confirmation standard
        $(document).on('submit', '.delete-form', function (e) {
            e.preventDefault();
            window.confirmDelete(this, 'Hapus kategori inventaris ini?');
        });
    });
</script>
@endpush

            </div>
        </div>
    </section>
</div>

@endsection
