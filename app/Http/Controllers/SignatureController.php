<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signature;
use App\Models\SignatureVerification;
use App\Models\Letter;
use App\Models\LetterConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SignatureController extends Controller
{
    /**
     * Show signature pad for signing a document
     */
    public function pad($signable, $id)
    {
        // Find the signable model
        $model = $this->findSignableModel($signable, $id);
        if (!$model) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        // Check if user has already signed
        $existingSignature = $model->signatures()->where('user_id', Auth::id())->first();
        if ($existingSignature) {
            return redirect()->route('signatures.list', ['signable' => $signable, 'id' => $id])
                ->with('error', 'You have already signed this document.');
        }

        return view('signatures.pad', compact('model', 'signable', 'id'));
    }

    /**
     * Store a new signature
     */
    public function store(Request $request, $signable, $id)
    {
        $request->validate([
            'signature_image' => 'required|string',
            'signature_reason' => 'nullable|string|max:500',
        ]);

        $model = $this->findSignableModel($signable, $id);
        if (!$model) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        // Security check: Check if user has already signed
        $existingSignature = $model->signatures()->where('user_id', Auth::id())->first();
        if ($existingSignature) {
            return redirect()->route('signatures.list', ['signable' => $signable, 'id' => $id])
                ->with('error', 'You have already signed this document.');
        }

        // Generate initial signature hash (internal tracking)
        $signatureHash = Signature::generateSignatureHash(
            $request->signature_image,
            Auth::id(),
            $model->id
        );

        // Store signature record
        $signature = Signature::create([
            'user_id' => Auth::id(),
            'signable_type' => get_class($model),
            'signable_id' => $model->id,
            'signature_image' => $request->signature_image,
            'signature_hash' => $signatureHash,
            'signature_reason' => $request->signature_reason,
            'verification_token' => Str::random(64),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'signed_date' => now(),
        ]);

        // Integrate OpenSSL Digital Signature
        // Data to be locked cryptographically
        $dataToSign = $model->id . '|' . Auth::id() . '|' . $signature->signed_date->toDateTimeString();
        
        // Sign with OpenSSL (this updates signature_hash with the encrypted signature)
        $signature->signWithOpenSSL($dataToSign);

        return redirect()->route('signatures.list', ['signable' => $signable, 'id' => $id])
            ->with('success', 'Document signed successfully with OpenSSL digital signature.');
    }

    /**
     * List signatures for a document
     */
    public function list($signable, $id)
    {
        $model = $this->findSignableModel($signable, $id);
        if (!$model) {
            return redirect()->back()->with('error', 'Document not found.');
        }

        $signatures = $model->signatures()->with('signer', 'verifications')->get();

        return view('signatures.list', compact('model', 'signatures', 'signable', 'id'));
    }

    /**
     * View verification logs
     */
    public function logs()
    {
        $user = Auth::user();
        $query = Signature::with('signer', 'signable', 'verifications');

        // HR Administrator/Master Admin see all signatures
        if ($user->employee && !\App\Constants\Roles::isAdmin($user->employee->role->title)) {
            $query->where('user_id', $user->id);
        }

        $signatures = $query->latest()->paginate(20);

        return view('signatures.logs', compact('signatures'));
    }

    /**
     * Verify a signature (HR Administrator/Master Admin only)
     */
    public function verify(Request $request, Signature $signature)
    {
        // Authorization - HR Administrator/Master Admin only
        $user = Auth::user();
        $userRole = $user->employee->role->title ?? null;
        if (!\App\Constants\Roles::isAdmin($userRole)) {
            abort(403, 'Only HR Administrator or Master Admins can verify signatures.');
        }

        $request->validate([
            'status' => 'required|in:verified,rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        // Create verification record
        SignatureVerification::create([
            'signature_id' => $signature->id,
            'verified_by_id' => Auth::id(),
            'status' => $request->status,
            'remarks' => $request->remarks,
            'verification_date' => now(),
        ]);

        // Update signature status
        $signature->update([
            'is_verified' => $request->status === 'verified'
        ]);

        return redirect()->back()->with('success', 'Signature ' . $request->status . ' successfully.');
    }

    /**
     * Download signed document as PDF
     */
    public function download(Signature $signature)
    {
        // Authorization check
        $user = Auth::user();
        if ($signature->user_id !== $user->id && ($user->employee && !\App\Constants\Roles::isAdmin($user->employee->role->title) && $user->employee->role->title !== 'Manager / Unit Head')) {
            abort(403, 'Unauthorized action.');
        }

        // Eager load relationships for PDF rendering
        $signature->load('signer', 'verifications.verifier');

        // Get the signable model
        $model = $signature->signable;

        if ($model instanceof Letter) {
            // Fetch all signatures for this document to show in PDF
            $allSignatures = $model->signatures()->with('signer.employee.role')->get();

            // Build verification URL for QR code (for the specific signature being downloaded)
            $verificationUrl = route('signatures.public-verify', [
                'id' => $signature->id,
                'token' => $signature->verification_token,
            ]);

            // Generate PDF with signatures
            $config = LetterConfiguration::first();
            $html = view('signatures.signed-letter-pdf', [
                'letter' => $model, 
                'signatures' => $allSignatures,
                'signature' => $signature, // Keep original for primary QR/context
                'verificationUrl' => $verificationUrl,
                'config' => $config
            ])->render();
            
            // Load PDF options to disable image processing if GD is not available
            $options = new \Dompdf\Options();
            $options->set('isRemoteEnabled', true);
            $options->set('chroot', public_path());
            
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->getDomPDF()->getOptions()->set('isRemoteEnabled', true);

            $filename = 'Surat_Tertandatangan_' . str_replace('/', '_', $model->letter_number ?? 'Draft') . '.pdf';
            return $pdf->download($filename);
        }

        return redirect()->back()->with('error', 'Cannot download this document type.');
    }

    /**
     * Validate/verify signature authenticity
     */
    public function validate(Signature $signature)
    {
        if ($signature->isValid()) {
            return response()->json([
                'valid' => true,
                'message' => 'Signature is authentic and has not been tampered with.',
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Signature validation failed. Document may have been tampered with.',
        ], 422);
    }

    /**
     * Public verification page (for QR code scans)
     */
    public function publicVerify(Request $request)
    {
        $signatureId = $request->query('id');
        $token = $request->query('token');

        if (!$signatureId || !$token) {
            abort(404, 'Invalid verification link.');
        }

        $signature = Signature::with('signer', 'signable', 'verifications.verifier')->find($signatureId);

        if (!$signature || $signature->verification_token !== $token) {
            abort(404, 'Signature not found or token mismatch.');
        }

        return view('signatures.public-verify', compact('signature'));
    }

    /**
     * Find signable model based on type and ID
     */
    private function findSignableModel($signable, $id)
    {
        switch ($signable) {
            case 'letter':
                return Letter::find($id);
            default:
                return null;
        }
    }
}
