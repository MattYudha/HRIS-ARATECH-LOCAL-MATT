@extends('layouts.dashboard')

@section('content')

<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Signature Verification Logs</li>
        </ol>
    </nav>

    <h3>Signature Verification Logs</h3>
</div>

<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-circle me-1"></i> Back to Dashboard
                </a>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Signer</th>
                            <th>Document</th>
                            <th>Document Type</th>
                            <th>Signed Date</th>
                            <th>Status</th>
                            <th>Verified</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signatures as $signature)
                            <tr>
                                <td>{{ $signature->signer->name }}</td>
                                <td>
                                    @if($signature->signable instanceof App\Models\Letter)
                                        {{ $signature->signable->subject }}
                                    @else
                                        {{ class_basename($signature->signable) }}
                                    @endif
                                </td>
                                <td>
                                    @if($signature->signable instanceof App\Models\Letter)
                                        <span class="badge bg-info">Letter</span>
                                    @else
                                        <span class="badge bg-secondary">{{ class_basename($signature->signable) }}</span>
                                    @endif
                                </td>
                                <td>{{ $signature->signed_date->format('d M Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $signature->is_verified ? 'success' : 'warning' }}">
                                        {{ $signature->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($signature->verified_at)
                                        {{ $signature->verified_at->format('d M Y H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        @if($signature->signable instanceof App\Models\Letter)
                                            <a href="{{ route('letters.show', $signature->signable) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="View Document">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('signatures.validate', $signature) }}" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           onclick="validateSignature(event, this)" 
                                           title="Validate Signature">
                                            <i class="bi bi-check2-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No signatures found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

     <!-- Pagination -->
@if ($signatures->hasPages())
        </div>
        {{-- Tombol halaman --}}
        <div class="col-md-12 d-flex justify-content-md-end justify-content-center">
            {{ $signatures->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
    </div>


<script>
function validateSignature(event, element) {
    event.preventDefault();
    const url = element.href;

    Swal.fire({
        title: 'Validasi',
        text: 'Sedang memvalidasi tanda tangan...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(url)
        .then(response => response.json())
        .then(data => {
            Swal.close();
            if (data.valid) {
                Swal.fire('Valid', data.message, 'success');
                element.classList.add('btn-success');
                element.classList.remove('btn-outline-secondary');
            } else {
                Swal.fire('Invalid', data.message, 'error');
                element.classList.add('btn-danger');
                element.classList.remove('btn-outline-secondary');
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire('Error', 'Gagal memvalidasi: ' + error.message, 'error');
        });
}
</script>
@endsection
