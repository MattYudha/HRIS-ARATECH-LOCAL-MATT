@extends('layouts.dashboard')

@section('content')
<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Letter Archives</li>
        </ol>
    </nav>

    <h3>Letter Archives</h3>
</div>

<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Total Letters</th>
                            <th>Approved</th>
                            <th>Printed</th>
                            <th>Generated Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($archives as $archive)
                            <tr>
                                <td>{{ $archive->month }}</td>
                                <td>{{ $archive->year }}</td>
                                <td><span class="badge bg-primary">{{ $archive->total_letters }}</span></td>
                                <td><span class="badge bg-success">{{ $archive->approved_letters }}</span></td>
                                <td><span class="badge bg-info">{{ $archive->printed_letters }}</span></td>
                                <td>{{ $archive->generated_at->format('d M Y H:i') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('letter-archives.show', $archive) }}" 
                                       class="btn btn-sm btn-outline-info" 
                                       title="View Archive">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No archives found yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
