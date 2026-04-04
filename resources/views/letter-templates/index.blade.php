@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Letter Templates</li>
        </ol>
    </nav>

    <h3>Letter Templates</h3>
</div>

<div class="page-content">
    <div class="container-fluid">

        {{-- Button Create --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('letter-templates.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Create Template
                </a>
            </div>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            @forelse($templates as $template)
                <div class="col-md-6 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title">{{ $template->name }}</h5>
                            <p class="card-text text-muted">
                                {{ $template->description ?? '-' }}
                            </p>

                            <span class="badge bg-info mb-3 align-self-start">
                                {{ ucfirst($template->type) }}
                            </span>

                            {{-- Action Buttons --}}
                            <div class="d-flex gap-2 flex-wrap mt-auto">
                                <button class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contentModal{{ $template->id }}"
                                        title="View Content">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <a href="{{ route('letter-templates.edit', $template) }}"
                                   class="btn btn-sm btn-outline-warning"
                                   title="Edit Template">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('letter-templates.destroy', $template) }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger delete-confirm" title="Delete Template">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Preview --}}
                <div class="modal fade" id="contentModal{{ $template->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $template->name }} – Content Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="border p-3 bg-light">
                                    {!! $template->content !!}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button"
                                        class="btn btn-secondary"
                                        data-bs-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-md-12">
                    <div class="alert alert-info">
                        No templates found.
                        <a href="{{ route('letter-templates.create') }}">Create one</a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@push('scripts')
<script>
    $(function() {
        $('.delete-confirm').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            window.confirmDelete(form[0], 'Hapus template surat ini?');
        });
    });
</script>
@endpush
@endsection
