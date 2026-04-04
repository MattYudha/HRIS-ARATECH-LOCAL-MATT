@extends('layouts.dashboard')

@section('content')


<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory-categories.index') }}">Inventory Categories</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
        </ol>
    </nav>

    <h3>Edit Category</h3>
</div>

<div class="page-content">
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-12"> {{-- Lebar form lebih pas --}}
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

                        <form action="{{ route('inventory-categories.update', $category) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Category Name --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    Category Name <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control"
                                       value="{{ old('name', $category->name) }}"
                                       required
                                       placeholder="Enter category name">
                            </div>

                            {{-- Description --}}
                            <div class="mb-4">
                                <label for="description" class="form-label">
                                    Description
                                </label>
                                <textarea name="description"
                                          id="description"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Enter description">{{ old('description', $category->description) }}</textarea>
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-5">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>

                                <a href="{{ route('inventory-categories.index') }}"
                                   class="btn btn-secondary px-5">
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
