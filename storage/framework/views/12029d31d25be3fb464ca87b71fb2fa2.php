<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Tanda Tangan Digital</title>
    <script src="<?php echo e(asset('vendor/tailwind/tailwind.min.js')); ?>"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Tanda Tangan Terverifikasi</h1>
            <p class="text-gray-600 mt-2">Dokumen ini telah ditandatangani secara digital</p>
        </div>

        <!-- Document Info -->
        <?php if($signature->signable instanceof \App\Models\Letter): ?>
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h2 class="font-semibold text-gray-900 mb-3">Informasi Dokumen</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nomor Surat:</span>
                    <span class="font-medium"><?php echo e($signature->signable->letter_number ?? 'Draft'); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tipe:</span>
                    <span class="font-medium"><?php echo e(ucfirst($signature->signable->letter_type)); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Judul:</span>
                    <span class="font-medium"><?php echo e($signature->signable->subject); ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Signature Info -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
            <h2 class="font-semibold text-gray-900 mb-3">Informasi Penandatangan</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Ditandatangani oleh:</span>
                    <span class="font-medium"><?php echo e($signature->signer->name); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium"><?php echo e($signature->signer->email); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu:</span>
                    <span class="font-medium"><?php echo e($signature->created_at->format('d M Y H:i:s')); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">IP Address:</span>
                    <span class="font-medium font-mono text-xs"><?php echo e($signature->ip_address); ?></span>
                </div>
            </div>
        </div>

        <!-- Cryptographic Data -->
        <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
            <h2 class="font-semibold text-gray-900 mb-3">Data Kriptografi</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-600 block mb-1">OpenSSL Digital Signature:</span>
                    <code class="block p-2 bg-white rounded border text-[10px] break-all font-mono leading-relaxed"><?php echo e($signature->signature_hash); ?></code>
                    <?php if($signature->isValid()): ?>
                        <div class="mt-2 text-green-600 text-xs font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Cryptographically Verified
                        </div>
                    <?php else: ?>
                        <div class="mt-2 text-red-600 text-xs font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Invalid Digital Signature
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="text-gray-600 block mb-1">Token Verifikasi:</span>
                    <code class="block p-2 bg-white rounded border text-xs break-all font-mono"><?php echo e($signature->verification_token); ?></code>
                </div>
            </div>
        </div>

        <!-- Verification History -->
        <?php if($signature->verifications && $signature->verifications->count() > 0): ?>
        <div class="mb-6 p-4 bg-green-50 rounded-lg">
            <h2 class="font-semibold text-gray-900 mb-3">Riwayat Verifikasi</h2>
            <div class="space-y-3">
                <?php $__currentLoopData = $signature->verifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $verification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-sm bg-white p-3 rounded border">
                    <div class="flex justify-between mb-2">
                        <span class="font-medium text-gray-900"><?php echo e($verification->verifier->name); ?></span>
                        <span class="text-gray-600"><?php echo e($verification->created_at->format('d M Y H:i')); ?></span>
                    </div>
                    <?php if($verification->verification_note): ?>
                    <p class="text-gray-600 text-xs"><?php echo e($verification->verification_note); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Signature Preview -->
        <?php if($signature->signature_image): ?>
        <div class="mb-6">
            <h2 class="font-semibold text-gray-900 mb-3">Preview Tanda Tangan</h2>
            <div class="border rounded-lg p-4 bg-white">
                <img src="<?php echo e($signature->signature_image); ?>" alt="Signature" class="max-w-full h-auto mx-auto" style="max-height: 200px;">
            </div>
        </div>
        <?php endif; ?>

        <!-- Footer Info -->
        <div class="mt-8 pt-6 border-t text-center">
            <p class="text-xs text-gray-500">
                Dokumen ini diverifikasi melalui sistem HRApp.<br>
                Untuk pertanyaan, hubungi administrator sistem.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/signatures/public-verify.blade.php ENDPATH**/ ?>