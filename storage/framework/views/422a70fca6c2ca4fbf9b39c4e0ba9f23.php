<?php $__env->startSection('content'); ?>



<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Request Detail</h3>
                <p class="text-subtitle text-muted">Review changes for employee: <strong><?php echo e($approval->employee->fullname); ?></strong></p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('employee-approvals.index')); ?>">Approvals</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Comparison Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        <th>Old Data</th>
                                        <th>New Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $approval->new_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $newVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo e(ucwords(str_replace('_', ' ', $key))); ?></td>
                                        <td class="text-danger"><?php echo e($approval->old_data[$key] ?? '(empty)'); ?></td>
                                        <td class="text-success"><?php echo e($newVal); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Action</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label d-block text-muted small">Status</label>
                            <?php
                                $class = match($approval->status) {
                                    'pending' => 'bg-warning',
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default => 'bg-secondary'
                                };
                            ?>
                            <span class="badge <?php echo e($class); ?>"><?php echo e(ucfirst($approval->status)); ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block text-muted small">Requested By</label>
                            <span><?php echo e($approval->requester->name); ?></span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block text-muted small">Date</label>
                            <span><?php echo e($approval->created_at->format('d M Y, H:i')); ?></span>
                        </div>

                        <?php if($approval->status === 'pending'): ?>
                        <hr>
                        <form action="<?php echo e(route('employee-approvals.approve', $approval->id)); ?>" method="POST" class="mb-2">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Are you sure you want to approve these changes?')">
                                <i class="bi bi-check-circle me-2"></i> Approve Changes
                            </button>
                        </form>

                        <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="bi bi-x-circle me-2"></i> Reject Request
                        </button>
                        <?php endif; ?>

                        <?php if($approval->refusal_reason): ?>
                        <div class="alert alert-danger mt-3 small">
                            <strong>Reason:</strong><br>
                            <?php echo e($approval->refusal_reason); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('employee-approvals.reject', $approval->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="refusal_reason" class="form-label">Reason for Rejection</label>
                        <textarea class="form-control" name="refusal_reason" id="refusal_reason" rows="3" required placeholder="Explain why the changes were rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-content-footer p-3 text-end border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aratechnology-hris/htdocs/hr-app/resources/views/employee-approvals/show.blade.php ENDPATH**/ ?>