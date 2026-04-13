@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>Digital Signatures - {{ $model->subject ?? 'Document' }}</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('letters.show', $id) }}" class="btn btn-secondary">Back to Document</a>
            @php
                $hasSigned = $model->signatures()->where('user_id', Auth::id())->exists();
            @endphp
            @if(!$hasSigned)
                <a href="{{ route('signatures.pad', ['signable' => $signable, 'id' => $id]) }}" class="btn btn-primary">+ Add Signature</a>
            @endif
        </div>
    </div>
    
    <div class="alert alert-info" role="alert">
        <strong>Download Options:</strong>
        <ul class="mb-0 mt-2">
            <li><strong>Unsigned PDF:</strong> Available in the Document view before any signatures are added</li>
            <li><strong>Signed PDF:</strong> Available below for each signature - includes the digital signature image and metadata</li>
        </ul>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header pb-0">
            <h5>Registered Signatures</h5>
            <p class="text-muted small">List of all digital signatures for this document.</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 250px;">Signer Details</th>
                            <th style="width: 150px;">Signature</th>
                            <th>Security & Validation</th>
                            <th class="text-center" style="width: 120px;">QR Code</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signatures as $signature)
                        <tr>
                            <td>
                                <strong>{{ $signature->signer->name }}</strong><br>
                                <small class="text-muted"><i class="bi bi-clock"></i> {{ $signature->signed_date->format('d M Y H:i') }}</small><br>
                                <small class="text-muted"><i class="bi bi-laptop"></i> {{ $signature->ip_address ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="border rounded p-1 text-center" style="background: #fff;">
                                    <img src="{{ $signature->signature_image }}" alt="Signature" style="max-height: 40px; width: auto;">
                                </div>
                            </td>
                            <td>
                                <div class="mb-1">
                                    @php
                                        $lastVerification = $signature->verifications->last();
                                        $isRejected = $lastVerification && $lastVerification->status === 'rejected';
                                    @endphp
                                    
                                    @if($signature->is_verified)
                                        <span class="badge bg-success mb-1">Verified</span>
                                    @elseif($isRejected)
                                        <span class="badge bg-danger mb-1">Rejected</span>
                                    @else
                                        <span class="badge bg-warning mb-1">Pending</span>
                                    @endif

                                    @if($signature->isValid())
                                        <span class="badge bg-info mb-1"><i class="bi bi-shield-check"></i> OpenSSL Valid</span>
                                    @else
                                        <span class="badge bg-danger mb-1"><i class="bi bi-shield-exclamation"></i> OpenSSL Invalid</span>
                                    @endif
                                </div>
                                <div style="font-size: 9px; line-height: 1.2;">
                                    <span class="text-muted">Token:</span> <code class="text-dark">{{ substr($signature->verification_token, 0, 16) }}...</code>
                                </div>
                            </td>
                            <td class="text-center">
                                @php
                                    $verificationUrl = route('signatures.public-verify', [
                                        'id' => $signature->id,
                                        'token' => $signature->verification_token,
                                    ]);
                                @endphp
                                <div style="display:inline-block; border:1px solid #ddd; padding:4px; background:#fff;">
                                    {!! QrCode::size(50)->margin(0)->generate($verificationUrl) !!}
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    @php
                                        $userRole = Auth::user()->employee->role->title ?? null;
                                        $canVerify = \App\Constants\Roles::isAdmin($userRole);
                                    @endphp

                                    @if($canVerify && !$signature->is_verified)
                                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verifyModal{{ $signature->id }}" title="Verify">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    @endif

                                    @if($signature->is_verified || Auth::user()->id === $signature->user_id)
                                        <a href="{{ route('signatures.download', $signature) }}" class="btn btn-primary" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal within loop scope for each row -->
                        @if($canVerify)
                        <div class="modal fade" id="verifyModal{{ $signature->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content text-start">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Verify Signature: {{ $signature->signer->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('signatures.verify.fixed', $signature) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Decision</label>
                                                <select class="form-control" name="status" required>
                                                    <option value="">-- Select --</option>
                                                    <option value="verified">Approve Signature</option>
                                                    <option value="rejected">Reject Signature</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Remarks</label>
                                                <textarea class="form-control" name="remarks" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    No signatures yet. <a href="{{ route('signatures.pad', ['signable' => $signable, 'id' => $id]) }}" class="fw-bold">Add a signature</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
