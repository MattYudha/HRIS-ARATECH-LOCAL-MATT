<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <h3>Letter Details</h3>
</div>


<div class="page-content">
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <?php if($letter->status === 'draft' && Auth::user()->id === $letter->user_id): ?>
                <a href="<?php echo e(route('letters.edit', $letter)); ?>" class="btn btn-warning">Edit</a>
                <form method="POST" action="<?php echo e(route('letters.destroy', $letter)); ?>" style="display:inline;" onsubmit="return confirm('Delete this letter?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <form method="POST" action="<?php echo e(route('letters.submit', $letter)); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success">Submit for Approval</button>
                </form>
            <?php endif; ?>
            
            <?php if($letter->status === 'pending' && Auth::user()->id === $letter->user_id): ?>
                <a href="<?php echo e(route('letters.edit', $letter)); ?>" class="btn btn-warning">Update</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Letter Number:</strong> <?php echo e($letter->letter_number ?? 'Draft'); ?></p>
                    <p><strong>Status:</strong> <span class="badge bg-<?php echo e($letter->status === 'draft' ? 'secondary' : ($letter->status === 'pending' ? 'warning' : ($letter->status === 'approved' ? 'success' : 'info'))); ?>"><?php echo e(ucfirst($letter->status)); ?></span></p>
                    <p><strong>From:</strong> <?php echo e($letter->user->name); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Type:</strong> <?php echo e(ucfirst($letter->letter_type)); ?></p>
                    <p><strong>Created:</strong> <?php echo e($letter->created_date->format('d M Y H:i')); ?></p>
                    <?php if($letter->approver): ?>
                        <p><strong>Approved by:</strong> <?php echo e($letter->approver->name); ?></p>
                    <?php endif; ?>
                    <?php if($letter->purpose): ?>
                        <p><strong>Purpose:</strong> <?php echo e($letter->purpose); ?></p>
                    <?php endif; ?>
                    <?php if($letter->end_date): ?>
                        <p><strong>End Date:</strong> <?php echo e($letter->end_date); ?></p>
                    <?php endif; ?>
                    <?php if($letter->days): ?>
                        <p><strong>Days:</strong> <?php echo e($letter->days); ?></p>
                    <?php endif; ?>
                    <?php if($letter->period): ?>
                        <p><strong>Period:</strong> <?php echo e($letter->period); ?></p>
                    <?php endif; ?>
                    <?php if($letter->recommender_name): ?>
                        <p><strong>Recommender:</strong> <?php echo e($letter->recommender_name); ?></p>
                    <?php endif; ?>
                    <?php if($letter->reason): ?>
                        <p><strong>Reason:</strong> <?php echo e($letter->reason); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <hr>

            <h5><?php echo e($letter->subject); ?></h5>
            <div class="mt-3" style="border: 1px solid #ddd; padding: 20px; background: #f9f9f9;">
                <?php echo nl2br($letter->formatted_content); ?>

            </div>

            <?php if($letter->status === 'pending'): ?>
                <?php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canApprove = in_array($userRole, ['HR', 'Power User']);
                ?>
                <?php if($canApprove): ?>
                    <hr>
                    <div class="mt-4">
                        <form method="POST" action="<?php echo e(route('letters.approve', $letter)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>
                    </div>
                <?php else: ?>
                    <hr>
                    <div class="alert alert-info mt-3">
                        <strong>Pending Approval:</strong> This letter is awaiting approval from HR.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if($letter->status === 'approved'): ?>
                <?php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canPrint = in_array($userRole, ['HR', 'Power User']);
                ?>
                <?php if($canPrint): ?>
                    <hr>
                    <div class="mt-4">
                        <a href="<?php echo e(route('letters.export', $letter)); ?>" class="btn btn-outline-info">Download Draft PDF</a>
                        <form method="POST" action="<?php echo e(route('letters.print', $letter)); ?>" style="display:inline;">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-primary">Mark as Printed</button>
                        </form>
                    </div>
                <?php else: ?>
                    <hr>
                    <div class="alert alert-success mt-3">
                        <strong>Approved:</strong> This letter has been approved by HR.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if($letter->status === 'printed'): ?>
                <?php
                    $userRole = Auth::user()->employee->role->title ?? null;
                    $canPrint = in_array($userRole, ['HR', 'Power User']);
                ?>
                <?php if($canPrint): ?>
                    <hr>
                    <div class="mt-4">
                        <a href="<?php echo e(route('letters.export', $letter)); ?>" class="btn btn-outline-info">Download Draft PDF</a>
                    </div>
                <?php else: ?>
                    <hr>
                    <div class="alert alert-success mt-3">
                        <strong>Completed:</strong> This letter has been printed.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Digital Signatures Section -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Digital Signatures</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <?php
                        $signatures = $letter->signatures()->count();
                    ?>
                    <p><strong>Total Signatures:</strong> <?php echo e($signatures); ?></p>
                    
                    <?php if($letter->status !== 'draft'): ?>
                        <?php
                            $hasSigned = $letter->signatures()->where('user_id', Auth::id())->exists();
                        ?>
                        <?php if(!$hasSigned): ?>
                            <a href="<?php echo e(route('signatures.pad', ['signable' => 'letter', 'id' => $letter->id])); ?>" class="btn btn-primary">+ Sign Document</a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('signatures.list', ['signable' => 'letter', 'id' => $letter->id])); ?>" class="btn btn-info">View All Signatures</a>
                        
                        <?php if($letter->signatures()->exists()): ?>
                            <a href="<?php echo e(route('signatures.download', $letter->signatures()->first())); ?>" class="btn btn-success">
                                <i class="bi bi-file-earmark-check"></i> Download Signed PDF (Final)
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning mt-2 mb-0">
                            <small>You can sign this document after it has been submitted for approval.</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Reject Modal -->
<?php
    $userRole = Auth::user()->employee->role->title ?? null;
    $canReject = in_array($userRole, ['HR', 'Power User']);
?>
<?php if($canReject): ?>
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Letter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('letters.reject', $letter)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Letter</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/letters/show.blade.php ENDPATH**/ ?>