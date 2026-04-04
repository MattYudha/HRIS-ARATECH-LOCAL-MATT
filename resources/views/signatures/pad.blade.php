@extends('layouts.dashboard')

@section('content')
<div class="page-heading">
    <h3>Digital Signature Pad</h3>
</div>
<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{ route('signatures.list', ['signable' => $signable, 'id' => $id]) }}" class="btn btn-secondary">Back to Signatures</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Draw Your Signature</h5>
                </div>
                <div class="card-body">
                    <form id="signatureForm" method="POST" action="{{ route('signatures.store', ['signable' => $signable, 'id' => $id]) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Signature Area</label>
                            <div style="border: 2px solid #ddd; border-radius: 4px; background: white;">
                                <canvas id="signaturePad" width="600" height="200" style="display: block; cursor: crosshair; background: white;"></canvas>
                            </div>
                            <small class="text-muted">Draw your signature in the box above</small>
                        </div>

                        <div class="mb-3">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary" id="clearBtn">Clear</button>
                                <button type="button" class="btn btn-outline-info" id="undoBtn">Undo</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="signature_reason" class="form-label">Reason for Signing (Optional)</label>
                            <textarea class="form-control" id="signature_reason" name="signature_reason" rows="2" placeholder="e.g., I approve this document"></textarea>
                        </div>

                        <input type="hidden" id="signature_image" name="signature_image">

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="signBtn">Sign Document</button>
                            <a href="{{ route('signatures.list', ['signable' => $signable, 'id' => $id]) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Document Info</h5>
                </div>
                <div class="card-body">
                    @if($model instanceof App\Models\Letter)
                        <p><strong>Document Type:</strong> Letter</p>
                        <p><strong>Letter Number:</strong> {{ $model->letter_number ?? 'Draft' }}</p>
                        <p><strong>Subject:</strong> {{ $model->subject }}</p>
                        <p><strong>Status:</strong> <span class="badge bg-{{ $model->status === 'approved' ? 'success' : 'warning' }}">{{ ucfirst($model->status) }}</span></p>
                        <p><strong>Signer:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Date:</strong> {{ now()->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5>Instructions</h5>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li>Draw your signature in the box using your mouse or touch screen</li>
                        <li>Use "Clear" to start over if needed</li>
                        <li>Use "Undo" to undo the last stroke</li>
                        <li>Add a reason for signing (optional)</li>
                        <li>Click "Sign Document" to complete</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="{{ asset('vendor/signature-pad/signature_pad.umd.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signaturePad');
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgb(255,255,255)',
        penColor: '#000000',
        minDistance: 5,
        minWidth: 2,
        maxWidth: 3,
        throttle: 16,
        velocityFilterWeight: 0.7,
        onBegin: function() {},
        onEnd: function() {}
    });

    // Resize canvas
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext('2d').scale(ratio, ratio);
    }
    resizeCanvas();

    // Clear button
    document.getElementById('clearBtn').addEventListener('click', function() {
        signaturePad.clear();
    });

    // Undo button
    document.getElementById('undoBtn').addEventListener('click', function() {
        const data = signaturePad.toData();
        if (data) {
            data.pop();
            signaturePad.fromData(data);
        }
    });

    // Form submission
    document.getElementById('signatureForm').addEventListener('submit', function(e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            alert('Please draw your signature first!');
            return false;
        }

        const signatureData = canvas.toDataURL('image/png');
        document.getElementById('signature_image').value = signatureData;
    });
});
</script>
@endsection
