<?php $__env->startSection('content'); ?>

<div class="page-heading mb-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Signature Verification Logs</li>
        </ol>
    </nav>

    <h3>Signature Verification Logs</h3>
</div>

<div class="page-content">
    <div class="container-fluid">

        <div class="row mb-3">
            <div class="col-md-12">
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-secondary">
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
                        <?php $__empty_1 = true; $__currentLoopData = $signatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $signature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($signature->signer->name); ?></td>
                                <td>
                                    <?php if($signature->signable instanceof App\Models\Letter): ?>
                                        <?php echo e($signature->signable->subject); ?>

                                    <?php else: ?>
                                        <?php echo e(class_basename($signature->signable)); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($signature->signable instanceof App\Models\Letter): ?>
                                        <span class="badge bg-info">Letter</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e(class_basename($signature->signable)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($signature->signed_date->format('d M Y H:i')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($signature->is_verified ? 'success' : 'warning'); ?>">
                                        <?php echo e($signature->is_verified ? 'Verified' : 'Pending'); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($signature->verified_at): ?>
                                        <?php echo e($signature->verified_at->format('d M Y H:i')); ?>

                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1 flex-wrap">
                                        <?php if($signature->signable instanceof App\Models\Letter): ?>
                                            <a href="<?php echo e(route('letters.show', $signature->signable)); ?>" 
                                               class="btn btn-sm btn-info" 
                                               title="View Document">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('signatures.validate', $signature)); ?>" 
                                           class="btn btn-sm btn-outline-secondary" 
                                           onclick="validateSignature(event, this)" 
                                           title="Validate Signature">
                                            <i class="bi bi-check2-circle"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    No signatures found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

     <!-- Pagination -->
<?php if($signatures->hasPages()): ?>
        </div>
        
        <div class="col-md-12 d-flex justify-content-md-end justify-content-center">
            <?php echo e($signatures->onEachSide(1)->links('pagination::bootstrap-5')); ?>

        </div>
    </div>
<?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/signatures/logs.blade.php ENDPATH**/ ?>